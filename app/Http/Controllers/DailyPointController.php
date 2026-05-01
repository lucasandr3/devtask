<?php

namespace App\Http\Controllers;

use App\Enums\PunchType;
use App\Http\Requests\StoreDailyPointRequest;
use App\Http\Requests\StorePunchRequest;
use App\Http\Requests\UpdateDailyPointRequest;
use App\Models\DailyPoint;
use App\Services\DailyPointService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyPointController extends Controller
{
    public function __construct(
        private DailyPointService $dailyPointService
    ) {}

    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $points = $this->dailyPointService->getMonthlyPoints(auth()->id(), $month);
        
        // Verifica se é o mês atual (permite edição apenas no mês corrente)
        $isCurrentMonth = $month === Carbon::now()->format('Y-m');

        return view('daily-points.index', [
            'points' => $points,
            'currentMonth' => $month,
            'isCurrentMonth' => $isCurrentMonth,
        ]);
    }

    public function punch()
    {
        $today = Carbon::today();
        $point = DailyPoint::where('user_id', auth()->id())
            ->where('work_date', $today)
            ->first();

        // Se não há registro, a próxima ação é ENTRY
        if (!$point) {
            $nextPunchType = PunchType::ENTRY;
        } else {
            $nextPunchType = $point->getNextPunchType();
        }

        return view('daily-points.punch', [
            'point' => $point,
            'nextPunchType' => $nextPunchType,
            'today' => $today,
        ]);
    }

    public function storePunch(StorePunchRequest $request)
    {
        try {
            $type = PunchType::from($request->type);
            
            // Se uma hora foi informada, combina com a data de hoje
            $time = null;
            if ($request->time) {
                $today = Carbon::today();
                // Se o formato for apenas H:i, combina com a data de hoje
                if (preg_match('/^\d{2}:\d{2}$/', $request->time)) {
                    $time = Carbon::createFromFormat('Y-m-d H:i', $today->format('Y-m-d') . ' ' . $request->time);
                } else {
                    $time = Carbon::parse($request->time);
                }
            }

            $point = $this->dailyPointService->punch(auth()->id(), $type, $time);

            return redirect()->route('horas.registrar')
                ->with('success', 'Hora registrada com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function create()
    {
        return view('daily-points.create');
    }

    public function store(StoreDailyPointRequest $request)
    {
        try {
            $point = $this->dailyPointService->createOrUpdate(
                auth()->id(),
                $request->work_date,
                $request->entry_time,
                $request->lunch_out_time,
                $request->lunch_return_time,
                $request->exit_time,
                $request->extra_start_time,
                $request->extra_end_time,
                $request->notes
            );

            return redirect()->route('horas.index', ['month' => Carbon::parse($request->work_date)->format('Y-m')])
                ->with('success', 'Hora registrada com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit(DailyPoint $dailyPoint)
    {
        // Verifica se a hora pertence ao usuário
        if ($dailyPoint->user_id !== auth()->id()) {
            abort(403);
        }

        // Verifica se a hora é do mês atual (só pode editar horas do mês corrente)
        if ($dailyPoint->work_date->format('Y-m') !== Carbon::now()->format('Y-m')) {
            return redirect()->route('horas.index')
                ->withErrors(['error' => 'Não é possível editar horas de meses anteriores.']);
        }

        return view('daily-points.edit', [
            'point' => $dailyPoint,
        ]);
    }

    public function update(UpdateDailyPointRequest $request, DailyPoint $dailyPoint)
    {
        // Verifica se a hora pertence ao usuário
        if ($dailyPoint->user_id !== auth()->id()) {
            abort(403);
        }

        // Verifica se a hora é do mês atual (só pode editar horas do mês corrente)
        if ($dailyPoint->work_date->format('Y-m') !== Carbon::now()->format('Y-m')) {
            return back()->withErrors(['error' => 'Não é possível editar horas de meses anteriores.'])->withInput();
        }

        try {
            $this->dailyPointService->updateManually(
                $dailyPoint,
                $request->work_date,
                $request->entry_time,
                $request->lunch_out_time,
                $request->lunch_return_time,
                $request->exit_time,
                $request->extra_start_time,
                $request->extra_end_time,
                $request->notes
            );

            return redirect()->route('horas.index', ['month' => Carbon::parse($request->work_date)->format('Y-m')])
                ->with('success', 'Hora atualizada com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
