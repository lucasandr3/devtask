<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_account_id',
        'user_id',
        'message_id',
        'folder',
        'from_email',
        'from_name',
        'to_emails',
        'cc_emails',
        'bcc_emails',
        'subject',
        'body_text',
        'body_html',
        'is_read',
        'is_starred',
        'is_draft',
        'is_sent',
        'attachments',
        'received_at',
        'sent_at',
    ];

    protected $casts = [
        'to_emails' => 'array',
        'cc_emails' => 'array',
        'bcc_emails' => 'array',
        'attachments' => 'array',
        'is_read' => 'boolean',
        'is_starred' => 'boolean',
        'is_draft' => 'boolean',
        'is_sent' => 'boolean',
        'received_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function emailAccount(): BelongsTo
    {
        return $this->belongsTo(EmailAccount::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFromDisplayAttribute(): string
    {
        if ($this->from_name) {
            return "{$this->from_name} <{$this->from_email}>";
        }
        return $this->from_email;
    }

    public function getPreviewAttribute(): string
    {
        $text = strip_tags($this->body_text ?? $this->body_html ?? '');
        return strlen($text) > 100 ? substr($text, 0, 100) . '...' : $text;
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    public function markAsUnread(): void
    {
        $this->update(['is_read' => false]);
    }

    public function toggleStar(): void
    {
        $this->update(['is_starred' => !$this->is_starred]);
    }
}
