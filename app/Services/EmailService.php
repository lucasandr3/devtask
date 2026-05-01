<?php

namespace App\Services;

use App\Models\EmailAccount;
use App\Models\EmailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransportFactory;
use Symfony\Component\Mime\Email;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\Exceptions\ConnectionFailedException;

class EmailService
{
    /**
     * Mapeamento de pastas para diferentes provedores
     */
    protected array $folderMappings = [
        'sent' => [
            '[Gmail]/Sent Mail',
            '[Gmail]/E-mails enviados',
            'Sent',
            'Sent Items',
            'Sent Messages',
            'INBOX.Sent',
        ],
        'drafts' => [
            '[Gmail]/Drafts',
            '[Gmail]/Rascunhos',
            'Drafts',
            'Draft',
            'INBOX.Drafts',
        ],
        'starred' => [
            '[Gmail]/Starred',
            '[Gmail]/Com estrela',
            'Flagged',
            'INBOX.Flagged',
        ],
        'trash' => [
            '[Gmail]/Trash',
            '[Gmail]/Lixeira',
            'Trash',
            'Deleted Items',
            'INBOX.Trash',
        ],
    ];

    /**
     * Sincronizar emails de uma conta via IMAP (todas as pastas principais)
     */
    public function syncEmails(EmailAccount $account, int $limit = 50): array
    {
        $result = [
            'success' => false,
            'synced' => 0,
            'errors' => [],
            'folders_synced' => [],
        ];

        try {
            $client = $this->getImapClient($account);
            $client->connect();

            // Obter lista de pastas disponíveis
            $availableFolders = $this->getAvailableFolderNames($client);

            // Sincronizar INBOX
            $inboxResult = $this->syncFolderMessages($client, $account, 'INBOX', $limit);
            $result['synced'] += $inboxResult['synced'];
            if (!empty($inboxResult['errors'])) {
                $result['errors'] = array_merge($result['errors'], $inboxResult['errors']);
            }
            if ($inboxResult['synced'] > 0) {
                $result['folders_synced'][] = 'INBOX';
            }

            // Sincronizar Enviados
            $sentFolder = $this->findFolder($availableFolders, 'sent');
            if ($sentFolder) {
                $sentResult = $this->syncFolderMessages($client, $account, $sentFolder, $limit, true);
                $result['synced'] += $sentResult['synced'];
                if (!empty($sentResult['errors'])) {
                    $result['errors'] = array_merge($result['errors'], $sentResult['errors']);
                }
                if ($sentResult['synced'] > 0) {
                    $result['folders_synced'][] = 'Sent';
                }
            }

            // Sincronizar Rascunhos
            $draftsFolder = $this->findFolder($availableFolders, 'drafts');
            if ($draftsFolder) {
                $draftsResult = $this->syncFolderMessages($client, $account, $draftsFolder, $limit, false, true);
                $result['synced'] += $draftsResult['synced'];
                if (!empty($draftsResult['errors'])) {
                    $result['errors'] = array_merge($result['errors'], $draftsResult['errors']);
                }
                if ($draftsResult['synced'] > 0) {
                    $result['folders_synced'][] = 'Drafts';
                }
            }

            // Sincronizar Favoritos/Com Estrela (atualiza flag is_starred)
            $this->syncStarredMessages($client, $account, $availableFolders);

            // Atualizar data da última sincronização
            $account->update(['last_sync_at' => now()]);

            $client->disconnect();
            $result['success'] = true;

        } catch (ConnectionFailedException $e) {
            $result['errors'][] = 'Falha na conexão IMAP: ' . $e->getMessage();
            Log::error('IMAP Connection Failed', [
                'account_id' => $account->id,
                'error' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            $result['errors'][] = 'Erro: ' . $e->getMessage();
            Log::error('Email Sync Error', [
                'account_id' => $account->id,
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }

    /**
     * Obter nomes das pastas disponíveis
     */
    protected function getAvailableFolderNames(Client $client): array
    {
        $folderNames = [];
        try {
            foreach ($client->getFolders() as $folder) {
                $folderNames[] = $folder->full_name;
            }
        } catch (\Exception $e) {
            Log::warning('Erro ao listar pastas IMAP: ' . $e->getMessage());
        }
        return $folderNames;
    }

    /**
     * Encontrar pasta pelo tipo
     */
    protected function findFolder(array $availableFolders, string $type): ?string
    {
        if (!isset($this->folderMappings[$type])) {
            return null;
        }

        foreach ($this->folderMappings[$type] as $possibleName) {
            foreach ($availableFolders as $folder) {
                if (strcasecmp($folder, $possibleName) === 0) {
                    return $folder;
                }
            }
        }

        return null;
    }

    /**
     * Sincronizar mensagens de uma pasta específica
     */
    protected function syncFolderMessages(
        Client $client,
        EmailAccount $account,
        string $folderName,
        int $limit,
        bool $isSent = false,
        bool $isDraft = false
    ): array {
        $result = [
            'synced' => 0,
            'errors' => [],
        ];

        try {
            $folder = $client->getFolder($folderName);
            
            if (!$folder) {
                return $result;
            }

            $messages = $folder->messages()
                ->all()
                ->setFetchOrder('desc')
                ->limit($limit)
                ->get();

            foreach ($messages as $message) {
                try {
                    $this->saveMessage($account, $message, $folderName, $isSent, $isDraft);
                    $result['synced']++;
                } catch (\Exception $e) {
                    $result['errors'][] = "Erro ao salvar mensagem de {$folderName}: " . $e->getMessage();
                }
            }

        } catch (\Exception $e) {
            $result['errors'][] = "Erro ao sincronizar {$folderName}: " . $e->getMessage();
        }

        return $result;
    }

    /**
     * Sincronizar mensagens favoritas (com estrela/flagged)
     */
    protected function syncStarredMessages(Client $client, EmailAccount $account, array $availableFolders): void
    {
        try {
            // Primeiro, tentar buscar da pasta de favoritos (se existir, como no Gmail)
            $starredFolder = $this->findFolder($availableFolders, 'starred');
            
            if ($starredFolder) {
                $folder = $client->getFolder($starredFolder);
                if ($folder) {
                    $messages = $folder->messages()
                        ->all()
                        ->setFetchOrder('desc')
                        ->limit(50)
                        ->get();

                    foreach ($messages as $message) {
                        $messageId = $message->getMessageId()?->toString() ?? $message->getUid();
                        
                        // Atualizar is_starred no banco se existir
                        EmailMessage::where('email_account_id', $account->id)
                            ->where('message_id', $messageId)
                            ->update(['is_starred' => true]);
                    }
                }
            }

            // Também buscar mensagens com flag "Flagged" na INBOX
            $inbox = $client->getFolder('INBOX');
            if ($inbox) {
                $flaggedMessages = $inbox->messages()
                    ->where('FLAGGED')
                    ->setFetchOrder('desc')
                    ->limit(50)
                    ->get();

                foreach ($flaggedMessages as $message) {
                    $messageId = $message->getMessageId()?->toString() ?? $message->getUid();
                    
                    EmailMessage::where('email_account_id', $account->id)
                        ->where('message_id', $messageId)
                        ->update(['is_starred' => true]);
                }
            }

        } catch (\Exception $e) {
            Log::warning('Erro ao sincronizar favoritos: ' . $e->getMessage());
        }
    }

    /**
     * Sincronizar pasta específica
     */
    public function syncFolder(EmailAccount $account, string $folderName, int $limit = 50): array
    {
        $result = [
            'success' => false,
            'synced' => 0,
            'errors' => [],
        ];

        try {
            $client = $this->getImapClient($account);
            $client->connect();

            // Detectar tipo de pasta
            $isSent = $this->isSentFolder($folderName);
            $isDraft = $this->isDraftFolder($folderName);

            $folder = $client->getFolder($folderName);
            
            if (!$folder) {
                throw new \Exception("Pasta {$folderName} não encontrada");
            }

            $messages = $folder->messages()
                ->all()
                ->setFetchOrder('desc')
                ->limit($limit)
                ->get();

            foreach ($messages as $message) {
                try {
                    $this->saveMessage($account, $message, $folderName, $isSent, $isDraft);
                    $result['synced']++;
                } catch (\Exception $e) {
                    $result['errors'][] = $e->getMessage();
                }
            }

            $client->disconnect();
            $result['success'] = true;

        } catch (\Exception $e) {
            $result['errors'][] = $e->getMessage();
        }

        return $result;
    }

    /**
     * Verificar se é pasta de enviados
     */
    protected function isSentFolder(string $folderName): bool
    {
        foreach ($this->folderMappings['sent'] as $sentName) {
            if (strcasecmp($folderName, $sentName) === 0) {
                return true;
            }
        }
        return strtolower($folderName) === 'sent';
    }

    /**
     * Verificar se é pasta de rascunhos
     */
    protected function isDraftFolder(string $folderName): bool
    {
        foreach ($this->folderMappings['drafts'] as $draftsName) {
            if (strcasecmp($folderName, $draftsName) === 0) {
                return true;
            }
        }
        return strtolower($folderName) === 'drafts';
    }

    /**
     * Enviar email via SMTP
     */
    public function sendEmail(EmailAccount $account, array $data): array
    {
        $result = [
            'success' => false,
            'message_id' => null,
            'error' => null,
        ];

        try {
            // Criar transporte SMTP dinâmico
            $encryption = $account->smtp_encryption === 'none' ? '' : $account->smtp_encryption;
            $scheme = $encryption === 'ssl' ? 'smtps' : 'smtp';
            
            $dsn = new Dsn(
                $scheme,
                $account->smtp_host,
                $account->smtp_username ?? $account->email,
                $account->getDecryptedSmtpPassword(),
                $account->smtp_port
            );

            $factory = new EsmtpTransportFactory();
            $transport = $factory->create($dsn);

            // Criar email
            $email = (new Email())
                ->from($account->email)
                ->subject($data['subject']);

            // Adicionar destinatários
            foreach ($data['to'] as $to) {
                $email->addTo($to);
            }

            if (!empty($data['cc'])) {
                foreach ($data['cc'] as $cc) {
                    $email->addCc($cc);
                }
            }

            if (!empty($data['bcc'])) {
                foreach ($data['bcc'] as $bcc) {
                    $email->addBcc($bcc);
                }
            }

            // Corpo do email
            if (!empty($data['body_html'])) {
                $email->html($data['body_html']);
            }
            if (!empty($data['body_text'])) {
                $email->text($data['body_text']);
            }

            // Adicionar anexos
            if (!empty($data['attachments'])) {
                Log::info('Processando anexos para email', [
                    'total_anexos' => count($data['attachments']),
                    'anexos' => array_map(fn($a) => [
                        'name' => $a['name'],
                        'path' => $a['path'],
                        'mime' => $a['mime'],
                        'exists' => file_exists($a['path']),
                        'size' => file_exists($a['path']) ? filesize($a['path']) : 0,
                    ], $data['attachments'])
                ]);
                
                foreach ($data['attachments'] as $attachment) {
                    if (file_exists($attachment['path'])) {
                        $email->attachFromPath(
                            $attachment['path'],
                            $attachment['name'],
                            $attachment['mime']
                        );
                        Log::info('Anexo adicionado ao email', ['name' => $attachment['name']]);
                    } else {
                        Log::warning('Arquivo de anexo não encontrado', ['path' => $attachment['path']]);
                    }
                }
            } else {
                Log::info('Email sem anexos');
            }

            // Enviar
            $sentMessage = $transport->send($email);

            // Preparar metadados dos anexos para salvar no banco
            $attachmentsMeta = [];
            if (!empty($data['attachments'])) {
                foreach ($data['attachments'] as $attachment) {
                    $attachmentsMeta[] = [
                        'name' => $attachment['name'],
                        'mime' => $attachment['mime'],
                        'size' => filesize($attachment['path']),
                    ];
                }
            }

            // Salvar no banco como enviado
            $emailMessage = EmailMessage::create([
                'email_account_id' => $account->id,
                'user_id' => $account->user_id,
                'message_id' => $sentMessage->getMessageId(),
                'folder' => 'Sent',
                'from_email' => $account->email,
                'from_name' => $account->user->name,
                'to_emails' => $data['to'],
                'cc_emails' => $data['cc'] ?? [],
                'bcc_emails' => $data['bcc'] ?? [],
                'subject' => $data['subject'],
                'body_html' => $data['body_html'] ?? null,
                'body_text' => $data['body_text'] ?? strip_tags($data['body_html'] ?? ''),
                'attachments' => $attachmentsMeta,
                'is_read' => true,
                'is_sent' => true,
                'sent_at' => now(),
            ]);

            $result['success'] = true;
            $result['message_id'] = $emailMessage->id;

        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
            Log::error('Email Send Error', [
                'account_id' => $account->id,
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }

    /**
     * Testar conexão IMAP
     */
    public function testImapConnection(EmailAccount $account): array
    {
        try {
            $client = $this->getImapClient($account);
            $client->connect();
            
            $folders = $client->getFolders();
            $folderNames = [];
            foreach ($folders as $folder) {
                $folderNames[] = $folder->name;
            }
            
            $client->disconnect();

            return [
                'success' => true,
                'message' => 'Conexão IMAP estabelecida com sucesso!',
                'folders' => $folderNames,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Falha na conexão IMAP: ' . $e->getMessage(),
                'folders' => [],
            ];
        }
    }

    /**
     * Testar conexão SMTP
     */
    public function testSmtpConnection(EmailAccount $account): array
    {
        try {
            $encryption = $account->smtp_encryption === 'none' ? '' : $account->smtp_encryption;
            $scheme = $encryption === 'ssl' ? 'smtps' : 'smtp';
            
            $dsn = new Dsn(
                $scheme,
                $account->smtp_host,
                $account->smtp_username ?? $account->email,
                $account->getDecryptedSmtpPassword(),
                $account->smtp_port
            );

            $factory = new EsmtpTransportFactory();
            $transport = $factory->create($dsn);

            // Tentar estabelecer conexão
            $transport->start();

            return [
                'success' => true,
                'message' => 'Conexão SMTP estabelecida com sucesso!',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Falha na conexão SMTP: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Obter cliente IMAP configurado
     */
    protected function getImapClient(EmailAccount $account): Client
    {
        $cm = new ClientManager();
        
        $encryption = $account->imap_encryption === 'none' ? false : $account->imap_encryption;

        return $cm->make([
            'host' => $account->imap_host,
            'port' => $account->imap_port,
            'encryption' => $encryption,
            'validate_cert' => true,
            'username' => $account->imap_username ?? $account->email,
            'password' => $account->getDecryptedImapPassword(),
            'protocol' => 'imap',
        ]);
    }

    /**
     * Salvar mensagem do IMAP no banco de dados
     */
    protected function saveMessage(
        EmailAccount $account,
        $message,
        string $folder,
        bool $isSent = false,
        bool $isDraft = false
    ): EmailMessage {
        $messageId = $message->getMessageId()?->toString() ?? $message->getUid();
        
        // Verificar se já existe
        $existing = EmailMessage::where('email_account_id', $account->id)
            ->where('message_id', $messageId)
            ->first();

        if ($existing) {
            // Atualizar flags se necessário
            $updates = [];
            if ($isSent && !$existing->is_sent) {
                $updates['is_sent'] = true;
            }
            if ($isDraft && !$existing->is_draft) {
                $updates['is_draft'] = true;
            }
            if (!empty($updates)) {
                $existing->update($updates);
            }
            return $existing;
        }

        // Extrair destinatários
        $toAddresses = [];
        $ccAddresses = [];

        if ($message->getTo()) {
            foreach ($message->getTo() as $address) {
                $toAddresses[] = $address->mail;
            }
        }

        if ($message->getCc()) {
            foreach ($message->getCc() as $address) {
                $ccAddresses[] = $address->mail;
            }
        }

        // Extrair remetente
        $from = $message->getFrom()->first();
        $fromEmail = $from ? $from->mail : 'unknown@email.com';
        $fromName = $from ? $from->personal : null;

        // Extrair corpo
        $bodyHtml = $message->getHTMLBody();
        $bodyText = $message->getTextBody();

        // Extrair anexos
        $attachments = [];
        if ($message->hasAttachments()) {
            foreach ($message->getAttachments() as $attachment) {
                $attachments[] = [
                    'name' => $attachment->getName(),
                    'mime' => $attachment->getMimeType(),
                    'size' => $attachment->getSize(),
                ];
            }
        }

        // Normalizar nome da pasta para o banco
        $normalizedFolder = $this->normalizeFolder($folder);

        return EmailMessage::create([
            'email_account_id' => $account->id,
            'user_id' => $account->user_id,
            'message_id' => $messageId,
            'folder' => $normalizedFolder,
            'from_email' => $fromEmail,
            'from_name' => $fromName,
            'to_emails' => $toAddresses,
            'cc_emails' => $ccAddresses,
            'subject' => $message->getSubject()?->toString() ?? '(Sem assunto)',
            'body_html' => $bodyHtml,
            'body_text' => $bodyText ?? strip_tags($bodyHtml ?? ''),
            'is_read' => $message->getFlags()->contains('Seen'),
            'is_starred' => $message->getFlags()->contains('Flagged'),
            'is_sent' => $isSent,
            'is_draft' => $isDraft,
            'attachments' => $attachments,
            'received_at' => $message->getDate()?->toDate(),
            'sent_at' => $isSent ? $message->getDate()?->toDate() : null,
        ]);
    }

    /**
     * Normalizar nome da pasta
     */
    protected function normalizeFolder(string $folder): string
    {
        $lowerFolder = strtolower($folder);
        
        // Verificar se é uma pasta de enviados
        foreach ($this->folderMappings['sent'] as $sentName) {
            if (strcasecmp($folder, $sentName) === 0) {
                return 'Sent';
            }
        }
        
        // Verificar se é uma pasta de rascunhos
        foreach ($this->folderMappings['drafts'] as $draftsName) {
            if (strcasecmp($folder, $draftsName) === 0) {
                return 'Drafts';
            }
        }
        
        // Verificar se é INBOX
        if ($lowerFolder === 'inbox') {
            return 'INBOX';
        }
        
        return $folder;
    }

    /**
     * Listar pastas disponíveis no servidor IMAP
     */
    public function listFolders(EmailAccount $account): array
    {
        try {
            $client = $this->getImapClient($account);
            $client->connect();
            
            $folders = [];
            foreach ($client->getFolders() as $folder) {
                $folders[] = [
                    'name' => $folder->name,
                    'full_name' => $folder->full_name,
                    'messages' => $folder->examine()['exists'] ?? 0,
                ];
            }
            
            $client->disconnect();

            return [
                'success' => true,
                'folders' => $folders,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'folders' => [],
                'error' => $e->getMessage(),
            ];
        }
    }
}
