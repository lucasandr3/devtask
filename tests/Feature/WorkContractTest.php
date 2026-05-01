<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserWorkContract;
use App\Services\WorkContractService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkContractTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private WorkContractService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->service = app(WorkContractService::class);
    }

    public function test_get_active_contract_for_date(): void
    {
        $startDate = Carbon::now()->startOfMonth();
        
        UserWorkContract::create([
            'user_id' => $this->user->id,
            'monthly_minutes' => 13200,
            'start_date' => $startDate,
            'end_date' => null,
        ]);

        $contract = $this->service->getActiveContractForDate($this->user->id, Carbon::now());

        $this->assertNotNull($contract);
        $this->assertEquals(13200, $contract->monthly_minutes);
    }

    public function test_contract_not_active_after_end_date(): void
    {
        $startDate = Carbon::now()->subMonths(2)->startOfMonth();
        $endDate = Carbon::now()->subMonth()->endOfMonth();
        
        UserWorkContract::create([
            'user_id' => $this->user->id,
            'monthly_minutes' => 13200,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $contract = $this->service->getActiveContractForDate($this->user->id, Carbon::now());

        $this->assertNull($contract);
    }

    public function test_validate_no_overlap(): void
    {
        $startDate = Carbon::now()->startOfMonth();
        
        UserWorkContract::create([
            'user_id' => $this->user->id,
            'monthly_minutes' => 13200,
            'start_date' => $startDate,
            'end_date' => null,
        ]);

        // Tentar criar outro contrato no mesmo período
        $hasOverlap = $this->service->validateNoOverlap(
            $this->user->id,
            Carbon::now()->addDays(5),
            null
        );

        $this->assertFalse($hasOverlap);
    }
}
