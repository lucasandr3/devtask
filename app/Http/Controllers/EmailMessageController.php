<?php

namespace App\Http\Controllers;

use App\Models\EmailAccount;
use App\Models\EmailMessage;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailMessageController extends Controller
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    public function index(Request $request)
    {
        $folder = $request->get('folder', 'inbox');
        $accountId = $request->get('account');
        
        $accounts = Auth::user()->emailAccounts()->where('is_active', true)->get();
        
        $query = EmailMessage::where('user_id', Auth::id());
        
        if ($accountId) {
            $query->where('email_account_id', $accountId);
        }
        
        // Filtrar por pasta
        switch ($folder) {
            case 'inbox':
                $query->where('folder', 'INBOX')->where('is_sent', false);
                break;
            case 'sent':
                $query->where('is_sent', true);
                break;
            case 'starred':
                $query->where('is_starred', true);
                break;
            case 'drafts':
                $query->where('is_draft', true);
                break;
            default:
                $query->where('folder', $folder);
        }
        
        $messages = $query->orderBy('received_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Contadores
        $counts = [
            'inbox' => EmailMessage::where('user_id', Auth::id())->where('folder', 'INBOX')->where('is_sent', false)->count(),
            'unread' => EmailMessage::where('user_id', Auth::id())->where('is_read', false)->count(),
            'sent' => EmailMessage::where('user_id', Auth::id())->where('is_sent', true)->count(),
            'starred' => EmailMessage::where('user_id', Auth::id())->where('is_starred', true)->count(),
            'drafts' => EmailMessage::where('user_id', Auth::id())->where('is_draft', true)->count(),
        ];
        
        return view('emails.index', compact('messages', 'accounts', 'folder', 'counts', 'accountId'));
    }

    public function show(EmailMessage $emailMessage)
    {
        $this->authorize('view', $emailMessage);
        
        // Marcar como lido
        if (!$emailMessage->is_read) {
            $emailMessage->markAsRead();
        }
        
        return view('emails.show', compact('emailMessage'));
    }

    public function create()
    {
        $accounts = Auth::user()->emailAccounts()->where('is_active', true)->get();
        
        if ($accounts->isEmpty()) {
            return redirect()->route('configuracoes.emails.index')
                ->with('warning', 'Você precisa configurar uma conta de email primeiro.');
        }
        
        return view('emails.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email_account_id' => 'required|exists:email_accounts,id',
            'to_emails' => 'required|string',
            'cc_emails' => 'nullable|string',
            'bcc_emails' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'is_draft' => 'nullable|boolean',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // Max 10MB por arquivo
        ]);

        $account = EmailAccount::findOrFail($validated['email_account_id']);
        $this->authorize('view', $account);

        // Processar emails
        $toEmails = array_filter(array_map('trim', explode(',', $validated['to_emails'])));
        $ccEmails = $validated['cc_emails'] ? array_filter(array_map('trim', explode(',', $validated['cc_emails']))) : [];
        $bccEmails = $validated['bcc_emails'] ? array_filter(array_map('trim', explode(',', $validated['bcc_emails']))) : [];

        // Processar anexos
        $attachments = [];
        if ($request->hasFile('attachments')) {
            \Log::info('Arquivos recebidos no controller', [
                'total' => count($request->file('attachments')),
                'all_files' => $request->allFiles(),
            ]);
            
            foreach ($request->file('attachments') as $file) {
                $attachments[] = [
                    'path' => $file->getRealPath(),
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                ];
            }
            
            \Log::info('Anexos processados', ['attachments' => $attachments]);
        } else {
            \Log::info('Nenhum arquivo recebido no controller', [
                'has_attachments' => $request->has('attachments'),
                'all_files' => $request->allFiles(),
            ]);
        }

        // Se é rascunho, salvar no banco
        if ($request->boolean('is_draft')) {
            EmailMessage::create([
                'email_account_id' => $account->id,
                'user_id' => Auth::id(),
                'from_email' => $account->email,
                'from_name' => Auth::user()->name,
                'to_emails' => $toEmails,
                'cc_emails' => $ccEmails,
                'bcc_emails' => $bccEmails,
                'subject' => $validated['subject'],
                'body_html' => $validated['body_html'],
                'body_text' => strip_tags($validated['body_html']),
                'is_draft' => true,
                'is_sent' => false,
                'folder' => 'Drafts',
            ]);

            return redirect()->route('emails.index', ['folder' => 'drafts'])
                ->with('success', 'Rascunho salvo com sucesso!');
        }

        // Enviar email via SMTP usando o serviço
        $result = $this->emailService->sendEmail($account, [
            'to' => $toEmails,
            'cc' => $ccEmails,
            'bcc' => $bccEmails,
            'subject' => $validated['subject'],
            'body_html' => $validated['body_html'],
            'body_text' => strip_tags($validated['body_html']),
            'attachments' => $attachments,
        ]);

        if ($result['success']) {
            return redirect()->route('emails.index', ['folder' => 'sent'])
                ->with('success', 'Email enviado com sucesso!');
        }

        return back()
            ->withInput()
            ->with('error', 'Erro ao enviar email: ' . $result['error']);
    }

    public function toggleRead(EmailMessage $emailMessage)
    {
        $this->authorize('update', $emailMessage);
        
        if ($emailMessage->is_read) {
            $emailMessage->markAsUnread();
        } else {
            $emailMessage->markAsRead();
        }

        return back()->with('success', 'Status atualizado!');
    }

    public function toggleStar(EmailMessage $emailMessage)
    {
        $this->authorize('update', $emailMessage);
        
        $emailMessage->toggleStar();

        return back()->with('success', 'Favorito atualizado!');
    }

    public function destroy(EmailMessage $emailMessage)
    {
        $this->authorize('delete', $emailMessage);
        
        $emailMessage->delete();

        return back()->with('success', 'Email excluído com sucesso!');
    }
}
