<?php

use App\Http\Controllers\AnnualDeclarationController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyFinancialController;
use App\Http\Controllers\DailyPointController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DasPaymentController;
use App\Http\Controllers\EmailAccountController;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MonthlyReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectHoursReportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SiteLeadController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskTimerController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('painel')
        : redirect()->route('entrar');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/painel', [DashboardController::class, 'index'])->name('painel');

    Route::resource('projetos', ProjectController::class)
        ->parameters(['projetos' => 'project']);

    Route::get('/tarefas', [TaskController::class, 'index'])->name('tarefas.index');
    Route::get('/tarefas/criar', [TaskController::class, 'create'])->name('tarefas.create');
    Route::post('/tarefas', [TaskController::class, 'store'])->name('tarefas.store');
    Route::get('/tarefas/{task}', [TaskController::class, 'show'])->name('tarefas.show');
    Route::get('/tarefas/{task}/editar', [TaskController::class, 'edit'])->name('tarefas.editar');
    Route::put('/tarefas/{task}', [TaskController::class, 'update'])->name('tarefas.update');
    Route::patch('/tarefas/{task}/status', [TaskController::class, 'updateStatus'])->name('tarefas.update-status');
    Route::post('/tarefas/{task}/timer/iniciar', [TaskTimerController::class, 'start'])->name('tarefas.timer.start');
    Route::post('/tarefas/{task}/timer/parar', [TaskTimerController::class, 'stop'])->name('tarefas.timer.stop');
    Route::get('/timer/ativo', [TaskTimerController::class, 'active'])->name('timer.active');
    Route::delete('/tarefas/{task}', [TaskController::class, 'destroy'])->name('tarefas.destroy');

    // Ponto eletrônico
    Route::get('/horas/registrar', [DailyPointController::class, 'punch'])->name('horas.registrar');
    Route::post('/horas/registrar', [DailyPointController::class, 'storePunch'])->name('horas.registrar.salvar');
    Route::get('/horas', [DailyPointController::class, 'index'])->name('horas.index');
    Route::get('/horas/criar', [DailyPointController::class, 'create'])->name('horas.criar');
    Route::post('/horas', [DailyPointController::class, 'store'])->name('horas.salvar');
    Route::get('/horas/{dailyPoint}/editar', [DailyPointController::class, 'edit'])->name('horas.editar');
    Route::put('/horas/{dailyPoint}', [DailyPointController::class, 'update'])->name('horas.atualizar');

    // Relatório mensal do funcionário
    Route::get('/relatorios-mensais', [MonthlyReportController::class, 'index'])->name('relatorios-mensais.index');
    Route::post('/relatorios-mensais/gerar', [MonthlyReportController::class, 'generate'])->name('relatorios-mensais.gerar');
    Route::get('/relatorios-mensais/aprovacoes', [MonthlyReportController::class, 'approvals'])->name('relatorios-mensais.aprovacoes');
    Route::get('/relatorios-mensais/{monthlyReport}', [MonthlyReportController::class, 'show'])->name('relatorios-mensais.mostrar');
    Route::get('/relatorios-mensais/{monthlyReport}/pdf', [MonthlyReportController::class, 'pdf'])->name('relatorios-mensais.pdf');
    Route::get('/relatorios-mensais/{monthlyReport}/espelho-horas', [MonthlyReportController::class, 'hoursMirror'])->name('relatorios-mensais.espelho-horas');
    Route::put('/relatorios-mensais/{id}/enviar', [MonthlyReportController::class, 'send'])->name('relatorios-mensais.enviar');
    Route::put('/relatorios-mensais/{id}/aprovar', [MonthlyReportController::class, 'approve'])->name('relatorios-mensais.aprovar');
    Route::put('/relatorios-mensais/{id}/rejeitar', [MonthlyReportController::class, 'reject'])->name('relatorios-mensais.rejeitar');

    // Relatórios
    Route::get('/relatorios', [ReportController::class, 'index'])->name('relatorios.index');
    Route::get('/relatorios/horas', [ReportController::class, 'hours'])->name('relatorios.horas');
    Route::get('/relatorios/horas/pdf', [ReportController::class, 'hoursPdf'])->name('relatorios.horas.pdf');
    Route::post('/relatorios/horas/enviar-email', [ReportController::class, 'sendHoursEmail'])->name('relatorios.horas.enviar-email');
    Route::get('/relatorios/financeiro', [ReportController::class, 'financial'])->name('relatorios.financeiro');
    Route::get('/relatorios/financeiro/pdf', [ReportController::class, 'financialPdf'])->name('relatorios.financeiro.pdf');
    Route::post('/relatorios/financeiro/enviar-email', [ReportController::class, 'sendFinancialEmail'])->name('relatorios.financeiro.enviar-email');
    Route::get('/relatorios/tarefas', [ReportController::class, 'tasks'])->name('relatorios.tarefas');
    Route::get('/relatorios/tarefas/pdf', [ReportController::class, 'tasksPdf'])->name('relatorios.tarefas.pdf');

    // Relatório de horas da empresa (admin/líder)
    Route::get('/relatorios/horas-empresa', [ProjectHoursReportController::class, 'index'])->name('relatorios.horas-empresa');

    // Gestão financeira da empresa
    Route::get('/financeiro', [CompanyFinancialController::class, 'index'])->name('financeiro.index');
    Route::resource('clientes', ClientController::class)->except(['show']);
    Route::resource('financeiro/lancamentos', FinancialTransactionController::class)
        ->parameters(['lancamentos' => 'lancamento'])
        ->names('financeiro.lancamentos')
        ->except(['show']);
    Route::post('/notas-fiscais/importar-xml', [InvoiceController::class, 'importXml'])->name('notas-fiscais.importar-xml');
    Route::resource('notas-fiscais', InvoiceController::class)
        ->parameters(['notas-fiscais' => 'invoice'])
        ->except(['show']);
    Route::get('/notas-fiscais/{invoice}/download', [InvoiceController::class, 'download'])->name('notas-fiscais.download');
    Route::get('/notas-fiscais/{invoice}/visualizar', [InvoiceController::class, 'view'])->name('notas-fiscais.visualizar');
    Route::get('/notas-fiscais/{invoice}', [InvoiceController::class, 'show'])->name('notas-fiscais.show');
    Route::resource('das', DasPaymentController::class)
        ->parameters(['das' => 'dasPayment'])
        ->except(['show']);
    Route::get('/declaracao-anual', [AnnualDeclarationController::class, 'index'])->name('declaracao-anual.index');
    Route::post('/declaracao-anual/gerar', [AnnualDeclarationController::class, 'generate'])->name('declaracao-anual.gerar');
    Route::get('/declaracao-anual/{annualDeclaration}', [AnnualDeclarationController::class, 'show'])->name('declaracao-anual.show');
    Route::get('/declaracao-anual/{annualDeclaration}/pdf', [AnnualDeclarationController::class, 'pdf'])->name('declaracao-anual.pdf');

    // Contatos do site (admin)
    Route::get('/contatos-site', [SiteLeadController::class, 'index'])->name('contatos-site.index');
    Route::get('/contatos-site/{siteLead}', [SiteLeadController::class, 'show'])->name('contatos-site.show');
    Route::patch('/contatos-site/{siteLead}/arquivar', [SiteLeadController::class, 'archive'])->name('contatos-site.archive');
    Route::delete('/contatos-site/{siteLead}', [SiteLeadController::class, 'destroy'])->name('contatos-site.destroy');

    // Equipe (admin)
    Route::get('/equipe', [TeamController::class, 'index'])->name('equipe.index');
    Route::get('/equipe/criar', [TeamController::class, 'create'])->name('equipe.create');
    Route::post('/equipe', [TeamController::class, 'store'])->name('equipe.store');
    Route::get('/equipe/{user}/editar', [TeamController::class, 'edit'])->name('equipe.edit');
    Route::patch('/equipe/{user}', [TeamController::class, 'update'])->name('equipe.update');
    Route::delete('/equipe/{user}', [TeamController::class, 'destroy'])->name('equipe.destroy');

    Route::get('/perfil', [ProfileController::class, 'edit'])->name('perfil.editar');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('perfil.atualizar');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('perfil.excluir');

    Route::get('/configuracoes', [SettingsController::class, 'index'])->name('configuracoes.index');
    Route::patch('/configuracoes/tema', [SettingsController::class, 'updateTheme'])->name('configuracoes.tema');
    Route::get('/configuracoes/emails', [EmailAccountController::class, 'index'])->name('configuracoes.emails.index');
    Route::post('/configuracoes/emails', [EmailAccountController::class, 'store'])->name('configuracoes.emails.store');
    Route::get('/configuracoes/emails/{emailAccount}/editar', [EmailAccountController::class, 'edit'])->name('configuracoes.emails.edit');
    Route::put('/configuracoes/emails/{emailAccount}', [EmailAccountController::class, 'update'])->name('configuracoes.emails.update');
    Route::delete('/configuracoes/emails/{emailAccount}', [EmailAccountController::class, 'destroy'])->name('configuracoes.emails.destroy');
    Route::post('/configuracoes/emails/{emailAccount}/sync', [EmailAccountController::class, 'sync'])->name('configuracoes.emails.sync');
    Route::post('/configuracoes/emails/{emailAccount}/default', [EmailAccountController::class, 'setDefault'])->name('configuracoes.emails.default');
});

require __DIR__.'/auth.php';
