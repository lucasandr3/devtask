<?php

namespace App\Http\Controllers;

use App\Models\DailyPoint;
use App\Models\MonthlyReport;
use App\Models\Invoice;
use App\Models\EmailAccount;
use App\Models\Task;
use App\Models\PullRequest;
use App\Services\FinancialReportService;
use App\Services\PdfService;
use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private FinancialReportService $financialReportService,
        private PdfService $pdfService,
        private EmailService $emailService
    ) {}

    /**
     * Menu principal de relatórios com cards
     */
    public function index()
    {
        $userId = auth()->id();
        $currentMonth = Carbon::now()->format('Y-m');

        // Estatísticas para os cards
        $monthlyReportsCount = MonthlyReport::where('user_id', $userId)->count();
        $pendingReportsCount = MonthlyReport::where('user_id', $userId)
            ->where('status', 'draft')
            ->count();

        // Horas do mês atual
        $currentMonthHours = DailyPoint::where('user_id', $userId)
            ->whereYear('work_date', Carbon::now()->year)
            ->whereMonth('work_date', Carbon::now()->month)
            ->get();
        
        $totalMinutes = $currentMonthHours->sum('total_minutes');
        $totalHours = floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;
        $currentMonthHoursFormatted = sprintf('%02d:%02d', $totalHours, $remainingMinutes);

        // Financeiro do mês
        $invoicesCount = Invoice::where('user_id', $userId)
            ->whereYear('data_emissao', Carbon::now()->year)
            ->whereMonth('data_emissao', Carbon::now()->month)
            ->count();
        
        $totalRevenue = Invoice::where('user_id', $userId)
            ->whereYear('data_emissao', Carbon::now()->year)
            ->whereMonth('data_emissao', Carbon::now()->month)
            ->sum('valor');
        $totalRevenueFormatted = 'R$ ' . number_format($totalRevenue, 2, ',', '.');

        // Tarefas do mês
        $tasksCount = Task::where('user_id', $userId)
            ->whereYear('work_date', Carbon::now()->year)
            ->whereMonth('work_date', Carbon::now()->month)
            ->count();
        
        $prsCount = PullRequest::where('user_id', $userId)
            ->whereYear('work_date', Carbon::now()->year)
            ->whereMonth('work_date', Carbon::now()->month)
            ->count();

        return view('reports.index', compact(
            'monthlyReportsCount',
            'pendingReportsCount',
            'currentMonthHoursFormatted',
            'invoicesCount',
            'totalRevenueFormatted',
            'tasksCount',
            'prsCount'
        ));
    }

    /**
     * Relatório de horas
     */
    public function hours(Request $request)
    {
        $userId = auth()->id();
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $month);

        $dailyPoints = DailyPoint::where('user_id', $userId)
            ->whereYear('work_date', $date->year)
            ->whereMonth('work_date', $date->month)
            ->orderBy('work_date', 'desc')
            ->get();

        // Calcular totais
        $totalMinutes = $dailyPoints->sum('total_minutes');
        $totalHours = floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;
        $totalWorked = sprintf('%02d:%02d', $totalHours, $remainingMinutes);

        // Dias trabalhados
        $workedDays = $dailyPoints->count();

        // Média de horas por dia
        $avgMinutes = $workedDays > 0 ? $totalMinutes / $workedDays : 0;
        $avgHours = floor($avgMinutes / 60);
        $avgRemaining = $avgMinutes % 60;
        $avgWorked = sprintf('%02d:%02d', $avgHours, $avgRemaining);

        return view('reports.hours', compact(
            'dailyPoints',
            'month',
            'totalWorked',
            'workedDays',
            'avgWorked'
        ));
    }

    /**
     * Relatório financeiro
     */
    public function financial(Request $request)
    {
        $userId = auth()->id();
        $month = $request->get('month', Carbon::now()->format('Y-m'));

        $financialData = $this->financialReportService->getMonthlyFinancial($userId, $month);

        return view('reports.financial', compact('financialData', 'month'));
    }

    /**
     * Gerar PDF do espelho de horas
     */
    public function hoursPdf(Request $request)
    {
        $userId = auth()->id();
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        
        $pdfContent = $this->pdfService->generateHoursMirrorPdf($userId, $month);
        
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="espelho-horas-' . $month . '.pdf"',
        ]);
    }

    /**
     * Enviar relatório de horas por email
     */
    public function sendHoursEmail(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'email' => 'required|email',
        ]);

        $userId = auth()->id();
        $month = $request->month;
        $destinatario = $request->email;

        // Verificar se tem conta de email configurada
        $emailAccount = EmailAccount::where('user_id', $userId)
            ->where('is_default', true)
            ->first();

        if (!$emailAccount) {
            $emailAccount = EmailAccount::where('user_id', $userId)->first();
        }

        if (!$emailAccount) {
            return back()->withErrors(['error' => 'Nenhuma conta de email configurada. Configure uma conta em Configurações > Emails.']);
        }

        try {
            // Gerar PDF
            $pdfContent = $this->pdfService->generateHoursMirrorPdf($userId, $month);
            
            // Salvar temporariamente
            $tempPath = storage_path('app/temp');
            if (!file_exists($tempPath)) {
                mkdir($tempPath, 0755, true);
            }
            
            $date = Carbon::createFromFormat('Y-m', $month);
            $filename = 'espelho-horas-' . $month . '.pdf';
            $filePath = $tempPath . '/' . $filename;
            file_put_contents($filePath, $pdfContent);

            // Enviar email
            $user = auth()->user();
            $result = $this->emailService->sendEmail($emailAccount, [
                'to' => [$destinatario],
                'subject' => 'Espelho de Horas - ' . $date->translatedFormat('F/Y'),
                'body_html' => '<p>Olá,</p><p>Segue em anexo o espelho de horas referente a <strong>' . $date->translatedFormat('F \\d\\e Y') . '</strong>.</p><p>Atenciosamente,<br>' . $user->name . '</p>',
                'body_text' => 'Segue em anexo o espelho de horas referente a ' . $date->translatedFormat('F \\d\\e Y') . '.',
                'attachments' => [
                    [
                        'path' => $filePath,
                        'name' => $filename,
                        'mime' => 'application/pdf',
                    ]
                ],
            ]);

            // Limpar arquivo temporário
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            if ($result['success']) {
                return back()->with('success', 'Relatório enviado com sucesso para ' . $destinatario);
            } else {
                return back()->withErrors(['error' => 'Erro ao enviar email: ' . ($result['error'] ?? 'Erro desconhecido')]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao enviar relatório: ' . $e->getMessage()]);
        }
    }

    /**
     * Gerar PDF do relatório financeiro
     */
    public function financialPdf(Request $request)
    {
        $userId = auth()->id();
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        
        $financialData = $this->financialReportService->getMonthlyFinancial($userId, $month);
        $pdfContent = $this->pdfService->generateFinancialReportPdf($userId, $month, $financialData);
        
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="relatorio-financeiro-' . $month . '.pdf"',
        ]);
    }

    /**
     * Enviar relatório financeiro por email
     */
    public function sendFinancialEmail(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'email' => 'required|email',
        ]);

        $userId = auth()->id();
        $month = $request->month;
        $destinatario = $request->email;

        // Verificar se tem conta de email configurada
        $emailAccount = EmailAccount::where('user_id', $userId)
            ->where('is_default', true)
            ->first();

        if (!$emailAccount) {
            $emailAccount = EmailAccount::where('user_id', $userId)->first();
        }

        if (!$emailAccount) {
            return back()->withErrors(['error' => 'Nenhuma conta de email configurada. Configure uma conta em Configurações > Emails.']);
        }

        try {
            // Gerar PDF
            $financialData = $this->financialReportService->getMonthlyFinancial($userId, $month);
            $pdfContent = $this->pdfService->generateFinancialReportPdf($userId, $month, $financialData);
            
            // Salvar temporariamente
            $tempPath = storage_path('app/temp');
            if (!file_exists($tempPath)) {
                mkdir($tempPath, 0755, true);
            }
            
            $date = Carbon::createFromFormat('Y-m', $month);
            $filename = 'relatorio-financeiro-' . $month . '.pdf';
            $filePath = $tempPath . '/' . $filename;
            file_put_contents($filePath, $pdfContent);

            // Enviar email
            $user = auth()->user();
            $result = $this->emailService->sendEmail($emailAccount, [
                'to' => [$destinatario],
                'subject' => 'Relatório Financeiro - ' . $date->translatedFormat('F/Y'),
                'body_html' => '<p>Olá,</p><p>Segue em anexo o relatório financeiro referente a <strong>' . $date->translatedFormat('F \\d\\e Y') . '</strong>.</p><p>Atenciosamente,<br>' . $user->name . '</p>',
                'body_text' => 'Segue em anexo o relatório financeiro referente a ' . $date->translatedFormat('F \\d\\e Y') . '.',
                'attachments' => [
                    [
                        'path' => $filePath,
                        'name' => $filename,
                        'mime' => 'application/pdf',
                    ]
                ],
            ]);

            // Limpar arquivo temporário
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            if ($result['success']) {
                return back()->with('success', 'Relatório enviado com sucesso para ' . $destinatario);
            } else {
                return back()->withErrors(['error' => 'Erro ao enviar email: ' . ($result['error'] ?? 'Erro desconhecido')]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao enviar relatório: ' . $e->getMessage()]);
        }
    }

    /**
     * Relatório de tarefas e pull requests
     */
    public function tasks(Request $request)
    {
        $userId = auth()->id();
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $month);

        $tasks = Task::where('user_id', $userId)
            ->with('pullRequests')
            ->whereYear('work_date', $date->year)
            ->whereMonth('work_date', $date->month)
            ->orderBy('work_date', 'desc')
            ->get();

        // Estatísticas
        $totalTasks = $tasks->count();
        $doneTasks = $tasks->where('status', \App\Enums\TaskStatus::DONE)->count();
        $doingTasks = $tasks->where('status', \App\Enums\TaskStatus::DOING)->count();
        $todoTasks = $tasks->where('status', \App\Enums\TaskStatus::TODO)->count();
        
        // Total de PRs
        $totalPRs = $tasks->sum(fn($task) => $task->pullRequests->count());

        return view('reports.tasks', compact(
            'tasks',
            'month',
            'totalTasks',
            'doneTasks',
            'doingTasks',
            'todoTasks',
            'totalPRs'
        ));
    }

    /**
     * Gerar PDF do relatório de tarefas
     */
    public function tasksPdf(Request $request)
    {
        $userId = auth()->id();
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        
        $pdfContent = $this->pdfService->generateTasksReportPdf($userId, $month);
        
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="relatorio-tarefas-' . $month . '.pdf"',
        ]);
    }
}
