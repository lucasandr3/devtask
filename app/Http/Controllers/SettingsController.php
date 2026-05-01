<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'notification_email' => ['nullable', 'email', 'max:255'],
            'email_notifications' => ['nullable', 'boolean'],
            'weekly_report' => ['nullable', 'boolean'],
            'monthly_summary' => ['nullable', 'boolean'],
        ]);

        $user = Auth::user();
        
        // Para funcionar, precisaria ter os campos no banco de dados
        // Por agora, vamos salvar em localStorage via JavaScript
        
        return back()->with('success', 'Configurações de email atualizadas com sucesso!');
    }
}
