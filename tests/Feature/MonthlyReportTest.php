<?php

namespace Tests\Feature;

use App\Models\DailyPoint;
use App\Models\MonthlyReport;
use App\Models\User;
use App\Models\UserWorkContract;
use App\Services\MonthlyReportService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonthlyReportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private MonthlyReportService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->service = app(MonthlyReportService::class);
    }

    public function test_generates_monthly_report(): void
    {
        // Criar contrato
        UserWorkContract::create([
            'user_id' => $this->user->id,
            'monthly_minutes' => 13200, // 220 horas
            'start_date' => Carbon::now()->startOfMonth(),
            'end_date' => null,
        ]);

        // Criar alguns pontos
        $date = Carbon::now()->startOfMonth()->addDays(1);
        DailyPoint::create([
            'user_id' => $this->user->id,
            'work_date' => $date,
            'normal_minutes' => 480, // 8 horas
            'extra_minutes' => 60, // 1 hora
            'total_minutes' => 540,
        ]);

        $report = $this->service->generate($this->user->id, Carbon::now()->format('Y-m'));

        $this->assertNotNull($report);
        $this->assertEquals(13200, $report->contract_minutes);
        $this->assertEquals(480, $report->normal_minutes);
        $this->assertEquals(60, $report->extra_minutes);
        $this->assertEquals(540, $report->total_minutes);
    }

    public function test_calculates_balance_correctly(): void
    {
        UserWorkContract::create([
            'user_id' => $this->user->id,
            'monthly_minutes' => 13200, // 220 horas
            'start_date' => Carbon::now()->startOfMonth(),
            'end_date' => null,
        ]);

        // Criar pontos que somam mais que o contrato
        $date = Carbon::now()->startOfMonth()->addDays(1);
        DailyPoint::create([
            'user_id' => $this->user->id,
            'work_date' => $date,
            'normal_minutes' => 7000,
            'extra_minutes' => 7000,
            'total_minutes' => 14000,
        ]);

        $report = $this->service->generate($this->user->id, Carbon::now()->format('Y-m'));

        // Saldo = 14000 - 13200 = 800 minutos (positivo)
        $this->assertEquals(800, $report->balance_minutes);
    }

    public function test_throws_exception_when_no_contract(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Nenhum contrato ativo encontrado');

        $this->service->generate($this->user->id, Carbon::now()->format('Y-m'));
    }
}
