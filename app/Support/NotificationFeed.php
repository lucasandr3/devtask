<?php

namespace App\Support;

use App\Enums\MonthlyReportStatus;
use App\Enums\TaskStatus;
use App\Models\MonthlyReport;
use App\Services\FinancialAlertService;
use Illuminate\Support\Collection;

class NotificationFeed
{
    public static function items(): Collection
    {
        if (! auth()->check()) {
            return collect();
        }

        $items = collect();
        $userId = auth()->id();

        MonthlyReport::query()
            ->where('user_id', $userId)
            ->where('status', MonthlyReportStatus::REJECTED)
            ->orderByDesc('updated_at')
            ->limit(3)
            ->get()
            ->each(function (MonthlyReport $report) use ($items) {
                $items->push([
                    'type' => 'report_rejected',
                    'icon' => 'report',
                    'title' => 'Relatório rejeitado',
                    'message' => 'Revise o relatório de '.$report->reference_month->translatedFormat('F Y'),
                    'url' => route('relatorios-mensais.index', ['month' => $report->reference_month->format('Y-m')]),
                    'at' => $report->updated_at,
                ]);
            });

        if (CurrentCompany::canApproveReports()) {
            $company = CurrentCompany::get();

            if ($company) {
                $memberIds = $company->users()->pluck('users.id');

                MonthlyReport::query()
                    ->with('user')
                    ->whereIn('user_id', $memberIds)
                    ->where('status', MonthlyReportStatus::SENT)
                    ->orderByDesc('updated_at')
                    ->limit(5)
                    ->get()
                    ->each(function (MonthlyReport $report) use ($items) {
                        $items->push([
                            'type' => 'approval',
                            'icon' => 'approve',
                            'title' => 'Aprovação pendente',
                            'message' => ($report->user->name ?? 'Membro').' — '.$report->reference_month->translatedFormat('F Y'),
                            'url' => route('relatorios-mensais.aprovacoes'),
                            'at' => $report->updated_at,
                        ]);
                    });
            }
        }

        if (CurrentCompany::canViewFinance() && CurrentCompany::id()) {
            app(FinancialAlertService::class)
                ->collect(CurrentCompany::id())
                ->take(5)
                ->each(function (array $alert) use ($items) {
                    $items->push([
                        'type' => $alert['type'],
                        'icon' => $alert['icon'],
                        'title' => $alert['title'],
                        'message' => $alert['message'].' · '.$alert['formatted_amount'],
                        'url' => $alert['url'],
                        'at' => $alert['at'],
                    ]);
                });
        }

        CurrentCompany::tasksQuery()
            ->where('status', TaskStatus::TODO)
            ->where('assigned_to', $userId)
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get()
            ->each(function ($task) use ($items) {
                $items->push([
                    'type' => 'task',
                    'icon' => 'tasks',
                    'title' => 'Tarefa pendente',
                    'message' => $task->title,
                    'url' => route('tarefas.show', $task),
                    'at' => $task->updated_at,
                ]);
            });

        return $items
            ->sortByDesc(fn (array $item) => $item['at'])
            ->values()
            ->take(15);
    }

    public static function count(): int
    {
        return self::items()->count();
    }
}
