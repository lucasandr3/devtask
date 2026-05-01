<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('registrar', [RegisteredUserController::class, 'create'])
        ->name('registrar');

    Route::post('registrar', [RegisteredUserController::class, 'store']);

    Route::get('entrar', [AuthenticatedSessionController::class, 'create'])
        ->name('entrar');

    Route::post('entrar', [AuthenticatedSessionController::class, 'store']);

    Route::get('esqueci-senha', [PasswordResetLinkController::class, 'create'])
        ->name('senha.solicitar');

    Route::post('esqueci-senha', [PasswordResetLinkController::class, 'store'])
        ->name('senha.email');

    Route::get('redefinir-senha/{token}', [NewPasswordController::class, 'create'])
        ->name('senha.redefinir');

    Route::post('redefinir-senha', [NewPasswordController::class, 'store'])
        ->name('senha.salvar');
});

Route::middleware('auth')->group(function () {
    Route::get('verificar-email', EmailVerificationPromptController::class)
        ->name('verificacao.aviso');

    Route::get('verificar-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verificacao.verificar');

    Route::post('email/notificacao-verificacao', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verificacao.enviar');

    Route::get('confirmar-senha', [ConfirmablePasswordController::class, 'show'])
        ->name('senha.confirmar');

    Route::post('confirmar-senha', [ConfirmablePasswordController::class, 'store']);

    Route::put('senha', [PasswordController::class, 'update'])->name('senha.atualizar');

    Route::post('sair', [AuthenticatedSessionController::class, 'destroy'])
        ->name('sair');
});
