<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DailyPointController;
use App\Http\Controllers\EmailAccountController;
use App\Http\Controllers\EmailMessageController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MonthlyReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PullRequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WorkContractController;
use App\Http\Controllers\DasPaymentController;
use App\Http\Controllers\AnnualDeclarationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('sales');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/painel', [DashboardController::class, 'index'])->name('painel');

    // Contratos
    Route::resource('contratos', WorkContractController::class)
        ->parameters(['contratos' => 'workContract']);

    // Horas Diárias
    Route::get('/horas/registrar', [DailyPointController::class, 'punch'])->name('horas.registrar');
    Route::post('/horas/registrar', [DailyPointController::class, 'storePunch'])->name('horas.registrar.salvar');
    Route::get('/horas', [DailyPointController::class, 'index'])->name('horas.index');
    Route::get('/horas/criar', [DailyPointController::class, 'create'])->name('horas.criar');
    Route::post('/horas', [DailyPointController::class, 'store'])->name('horas.salvar');
    Route::get('/horas/{dailyPoint}/editar', [DailyPointController::class, 'edit'])->name('horas.editar');
    Route::put('/horas/{dailyPoint}', [DailyPointController::class, 'update'])->name('horas.atualizar');

    // Tarefas
    Route::get('/tarefas', [TaskController::class, 'index'])->name('tarefas.index');
    Route::get('/tarefas/criar', [TaskController::class, 'create'])->name('tarefas.create');
    Route::post('/tarefas', [TaskController::class, 'store'])->name('tarefas.store');
    Route::get('/tarefas/{task}/editar', [TaskController::class, 'edit'])->name('tarefas.editar');
    Route::put('/tarefas/{task}', [TaskController::class, 'update'])->name('tarefas.update');
    Route::patch('/tarefas/{task}/status', [TaskController::class, 'updateStatus'])->name('tarefas.update-status');
    Route::delete('/tarefas/{task}', [TaskController::class, 'destroy'])->name('tarefas.destroy');

    // Pull Requests
    Route::resource('pull-requests', PullRequestController::class);

    // Relatórios - Menu Principal
    Route::get('/relatorios', [ReportController::class, 'index'])->name('relatorios.index');
    Route::get('/relatorios/horas', [ReportController::class, 'hours'])->name('relatorios.horas');
    Route::get('/relatorios/horas/pdf', [ReportController::class, 'hoursPdf'])->name('relatorios.horas.pdf');
    Route::post('/relatorios/horas/enviar-email', [ReportController::class, 'sendHoursEmail'])->name('relatorios.horas.enviar-email');
    Route::get('/relatorios/financeiro', [ReportController::class, 'financial'])->name('relatorios.financeiro');
    Route::get('/relatorios/financeiro/pdf', [ReportController::class, 'financialPdf'])->name('relatorios.financeiro.pdf');
    Route::post('/relatorios/financeiro/enviar-email', [ReportController::class, 'sendFinancialEmail'])->name('relatorios.financeiro.enviar-email');
    Route::get('/relatorios/tarefas', [ReportController::class, 'tasks'])->name('relatorios.tarefas');
    Route::get('/relatorios/tarefas/pdf', [ReportController::class, 'tasksPdf'])->name('relatorios.tarefas.pdf');

    // Relatórios Mensais
    Route::get('/relatorios/mensais', [MonthlyReportController::class, 'index'])->name('relatorios-mensais.index');
    Route::post('/relatorios/mensais/gerar', [MonthlyReportController::class, 'generate'])->name('relatorios-mensais.gerar');
    Route::get('/relatorios/mensais/{monthlyReport}', [MonthlyReportController::class, 'show'])->name('relatorios-mensais.mostrar');
    Route::get('/relatorios/mensais/{monthlyReport}/pdf', [MonthlyReportController::class, 'pdf'])->name('relatorios-mensais.pdf');
    Route::get('/relatorios/mensais/{monthlyReport}/espelho-horas', [MonthlyReportController::class, 'hoursMirror'])->name('relatorios-mensais.espelho-horas');
    Route::put('/relatorios/mensais/{id}/enviar', [MonthlyReportController::class, 'send'])->name('relatorios-mensais.enviar');
    Route::put('/relatorios/mensais/{id}/aprovar', [MonthlyReportController::class, 'approve'])->name('relatorios-mensais.aprovar');
    Route::put('/relatorios/mensais/{id}/rejeitar', [MonthlyReportController::class, 'reject'])->name('relatorios-mensais.rejeitar');

    // Notas Fiscais
    Route::get('/notas-fiscais', [InvoiceController::class, 'index'])->name('notas-fiscais.index');
    Route::get('/notas-fiscais/criar', [InvoiceController::class, 'create'])->name('notas-fiscais.create');
    Route::post('/notas-fiscais', [InvoiceController::class, 'store'])->name('notas-fiscais.store');
    Route::get('/notas-fiscais/{invoice}', [InvoiceController::class, 'show'])->name('notas-fiscais.show');
    Route::get('/notas-fiscais/{invoice}/editar', [InvoiceController::class, 'edit'])->name('notas-fiscais.edit');
    Route::put('/notas-fiscais/{invoice}', [InvoiceController::class, 'update'])->name('notas-fiscais.update');
    Route::delete('/notas-fiscais/{invoice}', [InvoiceController::class, 'destroy'])->name('notas-fiscais.destroy');
    Route::get('/notas-fiscais/{invoice}/visualizar', [InvoiceController::class, 'view'])->name('notas-fiscais.visualizar');
    Route::get('/notas-fiscais/{invoice}/download', [InvoiceController::class, 'download'])->name('notas-fiscais.download');

    // DAS
    Route::resource('das', DasPaymentController::class);

    // Declaração Anual
    Route::get('/declaracao-anual', [AnnualDeclarationController::class, 'index'])->name('declaracao-anual.index');
    Route::post('/declaracao-anual/gerar', [AnnualDeclarationController::class, 'generate'])->name('declaracao-anual.gerar');
    Route::get('/declaracao-anual/{annualDeclaration}', [AnnualDeclarationController::class, 'show'])->name('declaracao-anual.show');
    Route::get('/declaracao-anual/{annualDeclaration}/pdf', [AnnualDeclarationController::class, 'pdf'])->name('declaracao-anual.pdf');

    // Perfil
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('perfil.editar');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('perfil.atualizar');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('perfil.excluir');

    // Configurações
    Route::get('/configuracoes', [SettingsController::class, 'index'])->name('configuracoes.index');
    
    // Contas de Email
    Route::get('/configuracoes/emails', [EmailAccountController::class, 'index'])->name('configuracoes.emails.index');
    Route::post('/configuracoes/emails', [EmailAccountController::class, 'store'])->name('configuracoes.emails.store');
    Route::get('/configuracoes/emails/{emailAccount}/editar', [EmailAccountController::class, 'edit'])->name('configuracoes.emails.edit');
    Route::put('/configuracoes/emails/{emailAccount}', [EmailAccountController::class, 'update'])->name('configuracoes.emails.update');
    Route::delete('/configuracoes/emails/{emailAccount}', [EmailAccountController::class, 'destroy'])->name('configuracoes.emails.destroy');
    Route::post('/configuracoes/emails/{emailAccount}/padrao', [EmailAccountController::class, 'setDefault'])->name('configuracoes.emails.default');
    Route::post('/configuracoes/emails/{emailAccount}/testar', [EmailAccountController::class, 'testConnection'])->name('configuracoes.emails.test');
    Route::post('/configuracoes/emails/{emailAccount}/sincronizar', [EmailAccountController::class, 'sync'])->name('configuracoes.emails.sync');
    Route::post('/emails/sincronizar-todos', [EmailAccountController::class, 'syncAll'])->name('emails.sync-all');

    // Emails/Notificações
    Route::get('/emails', [EmailMessageController::class, 'index'])->name('emails.index');
    Route::get('/emails/novo', [EmailMessageController::class, 'create'])->name('emails.create');
    Route::post('/emails', [EmailMessageController::class, 'store'])->name('emails.store');
    Route::get('/emails/{emailMessage}', [EmailMessageController::class, 'show'])->name('emails.show');
    Route::post('/emails/{emailMessage}/lido', [EmailMessageController::class, 'toggleRead'])->name('emails.toggle-read');
    Route::post('/emails/{emailMessage}/favorito', [EmailMessageController::class, 'toggleStar'])->name('emails.toggle-star');
    Route::delete('/emails/{emailMessage}', [EmailMessageController::class, 'destroy'])->name('emails.destroy');
});

require __DIR__.'/auth.php';
