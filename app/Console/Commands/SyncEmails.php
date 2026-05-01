<?php

namespace App\Console\Commands;

use App\Models\EmailAccount;
use App\Services\EmailService;
use Illuminate\Console\Command;

class SyncEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:sync 
                            {--account= : ID de uma conta específica para sincronizar}
                            {--user= : ID do usuário para sincronizar todas as contas}
                            {--limit=50 : Número máximo de emails por conta}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza emails de todas as contas ativas via IMAP';

    /**
     * Execute the console command.
     */
    public function handle(EmailService $emailService)
    {
        $accountId = $this->option('account');
        $userId = $this->option('user');
        $limit = (int) $this->option('limit');

        $query = EmailAccount::where('is_active', true);

        if ($accountId) {
            $query->where('id', $accountId);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $accounts = $query->get();

        if ($accounts->isEmpty()) {
            $this->warn('Nenhuma conta de email ativa encontrada.');
            return 0;
        }

        $this->info("Sincronizando {$accounts->count()} conta(s) de email...");
        $this->newLine();

        $totalSynced = 0;
        $totalErrors = 0;

        foreach ($accounts as $account) {
            $this->info("📧 Sincronizando: {$account->name} ({$account->email})");
            
            $result = $emailService->syncEmails($account, $limit);

            if ($result['success']) {
                $foldersInfo = '';
                if (!empty($result['folders_synced'])) {
                    $folderNames = array_map(function ($folder) {
                        return match ($folder) {
                            'INBOX' => 'Entrada',
                            'Sent' => 'Enviados',
                            'Drafts' => 'Rascunhos',
                            default => $folder,
                        };
                    }, $result['folders_synced']);
                    $foldersInfo = ' [' . implode(', ', $folderNames) . ']';
                }
                $this->info("   ✓ {$result['synced']} email(s) sincronizado(s){$foldersInfo}");
                $totalSynced += $result['synced'];
            } else {
                $this->error("   ✗ Erro na sincronização");
                $totalErrors++;
            }

            if (!empty($result['errors'])) {
                foreach ($result['errors'] as $error) {
                    $this->warn("   - {$error}");
                }
            }

            $this->newLine();
        }

        $this->info("═══════════════════════════════════════");
        $this->info("Total sincronizado: {$totalSynced} email(s)");
        
        if ($totalErrors > 0) {
            $this->warn("Contas com erro: {$totalErrors}");
        }

        return $totalErrors > 0 ? 1 : 0;
    }
}
