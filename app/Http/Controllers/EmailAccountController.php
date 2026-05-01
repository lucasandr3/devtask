<?php

namespace App\Http\Controllers;

use App\Models\EmailAccount;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailAccountController extends Controller
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    public function index()
    {
        $accounts = Auth::user()->emailAccounts()->orderBy('is_default', 'desc')->orderBy('name')->get();
        
        return view('settings.email-accounts', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'provider' => 'required|in:gmail,outlook,yahoo,icloud,custom',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|in:tls,ssl,none',
            'imap_host' => 'nullable|string|max:255',
            'imap_port' => 'nullable|integer|min:1|max:65535',
            'imap_username' => 'nullable|string|max:255',
            'imap_password' => 'nullable|string',
            'imap_encryption' => 'nullable|in:tls,ssl,none',
            'is_default' => 'nullable|boolean',
        ]);

        $validated['user_id'] = Auth::id();
        
        // Se for definido como padrão, remover padrão de outras contas
        if ($request->boolean('is_default')) {
            Auth::user()->emailAccounts()->update(['is_default' => false]);
        }
        
        // Se for o primeiro email, definir como padrão
        if (Auth::user()->emailAccounts()->count() === 0) {
            $validated['is_default'] = true;
        }

        // Copiar senha SMTP para IMAP se IMAP estiver vazia
        if (empty($validated['imap_password']) && !empty($validated['smtp_password'])) {
            $validated['imap_password'] = $validated['smtp_password'];
        }

        $account = new EmailAccount($validated);
        
        // Aplicar configurações do provedor se não for custom
        if ($validated['provider'] !== 'custom') {
            $account->applyProviderConfig();
        }
        
        // Usar o email como username se não fornecido
        if (empty($account->smtp_username)) {
            $account->smtp_username = $validated['email'];
        }
        if (empty($account->imap_username)) {
            $account->imap_username = $validated['email'];
        }
        
        $account->save();

        return redirect()->route('configuracoes.emails.index')
            ->with('success', 'Conta de email adicionada com sucesso!');
    }

    public function edit(EmailAccount $emailAccount)
    {
        $this->authorize('update', $emailAccount);
        
        return view('settings.email-account-edit', compact('emailAccount'));
    }

    public function update(Request $request, EmailAccount $emailAccount)
    {
        $this->authorize('update', $emailAccount);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'provider' => 'required|in:gmail,outlook,yahoo,icloud,custom',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|in:tls,ssl,none',
            'imap_host' => 'nullable|string|max:255',
            'imap_port' => 'nullable|integer|min:1|max:65535',
            'imap_username' => 'nullable|string|max:255',
            'imap_password' => 'nullable|string',
            'imap_encryption' => 'nullable|in:tls,ssl,none',
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
        ]);

        // Se for definido como padrão, remover padrão de outras contas
        if ($request->boolean('is_default')) {
            Auth::user()->emailAccounts()->where('id', '!=', $emailAccount->id)->update(['is_default' => false]);
        }

        // Copiar senha SMTP para IMAP se uma nova senha foi fornecida
        if (!empty($validated['smtp_password']) && empty($validated['imap_password'])) {
            $validated['imap_password'] = $validated['smtp_password'];
        }

        // Não atualizar senha se estiver vazia
        if (empty($validated['smtp_password'])) {
            unset($validated['smtp_password']);
        }
        if (empty($validated['imap_password'])) {
            unset($validated['imap_password']);
        }

        $emailAccount->update($validated);

        return redirect()->route('configuracoes.emails.index')
            ->with('success', 'Conta de email atualizada com sucesso!');
    }

    public function destroy(EmailAccount $emailAccount)
    {
        $this->authorize('delete', $emailAccount);

        $wasDefault = $emailAccount->is_default;
        $emailAccount->delete();

        // Se era a conta padrão, definir outra como padrão
        if ($wasDefault) {
            Auth::user()->emailAccounts()->first()?->update(['is_default' => true]);
        }

        return redirect()->route('configuracoes.emails.index')
            ->with('success', 'Conta de email removida com sucesso!');
    }

    public function setDefault(EmailAccount $emailAccount)
    {
        $this->authorize('update', $emailAccount);

        Auth::user()->emailAccounts()->update(['is_default' => false]);
        $emailAccount->update(['is_default' => true]);

        return redirect()->route('configuracoes.emails.index')
            ->with('success', 'Conta de email definida como padrão!');
    }

    public function testConnection(EmailAccount $emailAccount)
    {
        $this->authorize('update', $emailAccount);

        $imapResult = $this->emailService->testImapConnection($emailAccount);
        $smtpResult = $this->emailService->testSmtpConnection($emailAccount);

        return response()->json([
            'success' => $imapResult['success'] && $smtpResult['success'],
            'imap' => $imapResult,
            'smtp' => $smtpResult,
        ]);
    }

    public function sync(EmailAccount $emailAccount)
    {
        $this->authorize('update', $emailAccount);

        $result = $this->emailService->syncEmails($emailAccount, 50);

        if ($result['success']) {
            $folderNames = [];
            if (!empty($result['folders_synced'])) {
                $folderNames = array_map(function ($folder) {
                    return match ($folder) {
                        'INBOX' => 'Entrada',
                        'Sent' => 'Enviados',
                        'Drafts' => 'Rascunhos',
                        default => $folder,
                    };
                }, $result['folders_synced']);
            }
            
            $foldersText = !empty($folderNames) ? ' (' . implode(', ', $folderNames) . ')' : '';
            
            return redirect()->route('emails.index')
                ->with('success', "Sincronização concluída! {$result['synced']} email(s) sincronizado(s){$foldersText}.");
        }

        return back()->with('error', 'Erro na sincronização: ' . implode(', ', $result['errors']));
    }

    /**
     * Sincronizar todas as contas do usuário
     */
    public function syncAll()
    {
        $accounts = Auth::user()->emailAccounts()->where('is_active', true)->get();
        
        if ($accounts->isEmpty()) {
            return back()->with('warning', 'Nenhuma conta de email ativa para sincronizar.');
        }

        $totalSynced = 0;
        $allFoldersSynced = [];
        $errors = [];

        foreach ($accounts as $account) {
            $result = $this->emailService->syncEmails($account, 50);
            
            if ($result['success']) {
                $totalSynced += $result['synced'];
                if (!empty($result['folders_synced'])) {
                    $allFoldersSynced = array_merge($allFoldersSynced, $result['folders_synced']);
                }
            } else {
                $errors[] = "{$account->name}: " . implode(', ', $result['errors']);
            }
        }

        // Remover duplicatas e formatar nomes das pastas
        $uniqueFolders = array_unique($allFoldersSynced);
        $folderNames = array_map(function ($folder) {
            return match ($folder) {
                'INBOX' => 'Entrada',
                'Sent' => 'Enviados',
                'Drafts' => 'Rascunhos',
                default => $folder,
            };
        }, $uniqueFolders);
        
        $foldersText = !empty($folderNames) ? ' (' . implode(', ', $folderNames) . ')' : '';

        if (empty($errors)) {
            return redirect()->route('emails.index')
                ->with('success', "Sincronização concluída! {$totalSynced} email(s) sincronizado(s) de {$accounts->count()} conta(s){$foldersText}.");
        }

        return redirect()->route('emails.index')
            ->with('warning', "Sincronização parcial: {$totalSynced} email(s){$foldersText}. Erros: " . implode('; ', $errors));
    }
}
