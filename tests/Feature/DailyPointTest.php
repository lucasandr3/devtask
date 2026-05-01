<?php

namespace Tests\Feature;

use App\Enums\PunchType;
use App\Models\DailyPoint;
use App\Models\User;
use App\Services\DailyPointService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyPointTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private DailyPointService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->service = app(DailyPointService::class);
    }

    public function test_punch_sequence(): void
    {
        // Entrada
        $point = $this->service->punch($this->user->id, PunchType::ENTRY);
        $this->assertNotNull($point->entry_time);
        $this->assertNull($point->lunch_out_time);

        // Saída almoço
        $point = $this->service->punch($this->user->id, PunchType::LUNCH_OUT);
        $this->assertNotNull($point->lunch_out_time);

        // Volta almoço
        $point = $this->service->punch($this->user->id, PunchType::LUNCH_RETURN);
        $this->assertNotNull($point->lunch_return_time);

        // Saída
        $point = $this->service->punch($this->user->id, PunchType::EXIT);
        $this->assertNotNull($point->exit_time);
    }

    public function test_cannot_punch_out_of_sequence(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Não é possível registrar este tipo de batida no momento');

        // Tentar saída almoço sem entrada
        $this->service->punch($this->user->id, PunchType::LUNCH_OUT);
    }

    public function test_cannot_punch_extra_before_exit(): void
    {
        $this->service->punch($this->user->id, PunchType::ENTRY);
        $this->service->punch($this->user->id, PunchType::LUNCH_OUT);
        $this->service->punch($this->user->id, PunchType::LUNCH_RETURN);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Não é possível registrar este tipo de batida no momento');

        // Tentar hora extra antes da saída
        $this->service->punch($this->user->id, PunchType::EXTRA_START);
    }

    public function test_can_punch_extra_after_exit(): void
    {
        $this->service->punch($this->user->id, PunchType::ENTRY);
        $this->service->punch($this->user->id, PunchType::LUNCH_OUT);
        $this->service->punch($this->user->id, PunchType::LUNCH_RETURN);
        $this->service->punch($this->user->id, PunchType::EXIT);

        // Agora pode registrar hora extra
        $point = $this->service->punch($this->user->id, PunchType::EXTRA_START);
        $this->assertNotNull($point->extra_start_time);

        $point = $this->service->punch($this->user->id, PunchType::EXTRA_END);
        $this->assertNotNull($point->extra_end_time);
    }

    public function test_calculates_minutes_correctly(): void
    {
        $entry = Carbon::today()->setTime(9, 0);
        $lunchOut = Carbon::today()->setTime(12, 0);
        $lunchReturn = Carbon::today()->setTime(13, 0);
        $exit = Carbon::today()->setTime(18, 0);

        $point = DailyPoint::create([
            'user_id' => $this->user->id,
            'work_date' => Carbon::today(),
            'entry_time' => $entry,
            'lunch_out_time' => $lunchOut,
            'lunch_return_time' => $lunchReturn,
            'exit_time' => $exit,
        ]);

        $point->calculateMinutes();

        // Manhã: 12:00 - 9:00 = 3 horas = 180 minutos
        // Tarde: 18:00 - 13:00 = 5 horas = 300 minutos
        // Total: 480 minutos = 8 horas
        $this->assertEquals(480, $point->normal_minutes);
        $this->assertEquals(0, $point->extra_minutes);
        $this->assertEquals(480, $point->total_minutes);
    }

    public function test_calculates_extra_minutes(): void
    {
        $point = DailyPoint::create([
            'user_id' => $this->user->id,
            'work_date' => Carbon::today(),
            'entry_time' => Carbon::today()->setTime(9, 0),
            'lunch_out_time' => Carbon::today()->setTime(12, 0),
            'lunch_return_time' => Carbon::today()->setTime(13, 0),
            'exit_time' => Carbon::today()->setTime(18, 0),
            'extra_start_time' => Carbon::today()->setTime(18, 30),
            'extra_end_time' => Carbon::today()->setTime(20, 30),
        ]);

        $point->calculateMinutes();

        // Extra: 20:30 - 18:30 = 2 horas = 120 minutos
        $this->assertEquals(120, $point->extra_minutes);
        $this->assertEquals(600, $point->total_minutes); // 480 + 120
    }
}
