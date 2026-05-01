<?php

namespace App\Services;

use App\Enums\PunchType;
use App\Models\DailyPoint;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DailyPointService
{
    public function punch(int $userId, PunchType $type, ?Carbon $time = null): DailyPoint
    {
        $workDate = Carbon::today();
        $punchTime = $time ?? Carbon::now();

        $point = DailyPoint::firstOrCreate(
            [
                'user_id' => $userId,
                'work_date' => $workDate,
            ]
        );

        if (!$point->canPunch($type)) {
            throw new \Exception('Não é possível registrar este tipo de batida no momento. Verifique a sequência correta.');
        }

        $field = $type->fieldName();
        // Combinar data do trabalho com hora do punch
        $point->$field = Carbon::parse($workDate->format('Y-m-d') . ' ' . $punchTime->format('H:i:s'));
        $point->calculateMinutes();
        $point->save();

        return $point->fresh();
    }

    public function calculateMinutes(DailyPoint $point): void
    {
        $point->calculateMinutes();
        $point->save();
    }

    public function getMonthlyPoints(int $userId, string $month): Collection
    {
        $date = Carbon::createFromFormat('Y-m', $month);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        return DailyPoint::where('user_id', $userId)
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->orderBy('work_date')
            ->get();
    }

    public function createOrUpdate(
        int $userId,
        string $workDate,
        ?string $entryTime = null,
        ?string $lunchOutTime = null,
        ?string $lunchReturnTime = null,
        ?string $exitTime = null,
        ?string $extraStartTime = null,
        ?string $extraEndTime = null,
        ?string $notes = null
    ): DailyPoint {
        $workDateCarbon = Carbon::parse($workDate);

        $point = DailyPoint::firstOrCreate(
            [
                'user_id' => $userId,
                'work_date' => $workDateCarbon,
            ]
        );

        return $this->updateManually(
            $point,
            $workDate,
            $entryTime,
            $lunchOutTime,
            $lunchReturnTime,
            $exitTime,
            $extraStartTime,
            $extraEndTime,
            $notes
        );
    }

    public function updateManually(
        DailyPoint $point,
        string $workDate,
        ?string $entryTime = null,
        ?string $lunchOutTime = null,
        ?string $lunchReturnTime = null,
        ?string $exitTime = null,
        ?string $extraStartTime = null,
        ?string $extraEndTime = null,
        ?string $notes = null
    ): DailyPoint {
        $workDateCarbon = Carbon::parse($workDate);

        $point->work_date = $workDateCarbon;

        // Atualiza os horários se fornecidos
        if ($entryTime) {
            $point->entry_time = Carbon::createFromFormat('Y-m-d H:i', $workDateCarbon->format('Y-m-d') . ' ' . $entryTime);
        } else {
            $point->entry_time = null;
        }

        if ($lunchOutTime) {
            $point->lunch_out_time = Carbon::createFromFormat('Y-m-d H:i', $workDateCarbon->format('Y-m-d') . ' ' . $lunchOutTime);
        } else {
            $point->lunch_out_time = null;
        }

        if ($lunchReturnTime) {
            $point->lunch_return_time = Carbon::createFromFormat('Y-m-d H:i', $workDateCarbon->format('Y-m-d') . ' ' . $lunchReturnTime);
        } else {
            $point->lunch_return_time = null;
        }

        if ($exitTime) {
            $point->exit_time = Carbon::createFromFormat('Y-m-d H:i', $workDateCarbon->format('Y-m-d') . ' ' . $exitTime);
        } else {
            $point->exit_time = null;
        }

        if ($extraStartTime) {
            $point->extra_start_time = Carbon::createFromFormat('Y-m-d H:i', $workDateCarbon->format('Y-m-d') . ' ' . $extraStartTime);
        } else {
            $point->extra_start_time = null;
        }

        if ($extraEndTime) {
            $point->extra_end_time = Carbon::createFromFormat('Y-m-d H:i', $workDateCarbon->format('Y-m-d') . ' ' . $extraEndTime);
        } else {
            $point->extra_end_time = null;
        }

        $point->notes = $notes;
        $point->calculateMinutes();
        $point->save();

        return $point->fresh();
    }
}
