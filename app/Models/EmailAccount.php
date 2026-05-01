<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class EmailAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'provider',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'imap_host',
        'imap_port',
        'imap_username',
        'imap_password',
        'imap_encryption',
        'is_active',
        'is_default',
        'last_sync_at',
    ];

    protected $casts = [
        'smtp_port' => 'integer',
        'imap_port' => 'integer',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'last_sync_at' => 'datetime',
    ];

    protected $hidden = [
        'smtp_password',
        'imap_password',
    ];

    // Configurações padrão por provedor
    public static array $providerConfigs = [
        'gmail' => [
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_encryption' => 'tls',
            'imap_host' => 'imap.gmail.com',
            'imap_port' => 993,
            'imap_encryption' => 'ssl',
        ],
        'outlook' => [
            'smtp_host' => 'smtp-mail.outlook.com',
            'smtp_port' => 587,
            'smtp_encryption' => 'tls',
            'imap_host' => 'outlook.office365.com',
            'imap_port' => 993,
            'imap_encryption' => 'ssl',
        ],
        'yahoo' => [
            'smtp_host' => 'smtp.mail.yahoo.com',
            'smtp_port' => 587,
            'smtp_encryption' => 'tls',
            'imap_host' => 'imap.mail.yahoo.com',
            'imap_port' => 993,
            'imap_encryption' => 'ssl',
        ],
        'icloud' => [
            'smtp_host' => 'smtp.mail.me.com',
            'smtp_port' => 587,
            'smtp_encryption' => 'tls',
            'imap_host' => 'imap.mail.me.com',
            'imap_port' => 993,
            'imap_encryption' => 'ssl',
        ],
    ];

    // Encrypt password before saving
    public function setSmtpPasswordAttribute($value): void
    {
        if ($value) {
            $this->attributes['smtp_password'] = Crypt::encryptString($value);
        }
    }

    public function setImapPasswordAttribute($value): void
    {
        if ($value) {
            $this->attributes['imap_password'] = Crypt::encryptString($value);
        }
    }

    // Decrypt password when accessing
    public function getDecryptedSmtpPassword(): ?string
    {
        $password = $this->attributes['smtp_password'] ?? null;
        if ($password) {
            try {
                return Crypt::decryptString($password);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function getDecryptedImapPassword(): ?string
    {
        $password = $this->attributes['imap_password'] ?? null;
        if ($password) {
            try {
                return Crypt::decryptString($password);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(EmailMessage::class);
    }

    public function unreadMessages(): HasMany
    {
        return $this->hasMany(EmailMessage::class)->where('is_read', false);
    }

    // Aplicar configurações do provedor
    public function applyProviderConfig(): void
    {
        if ($this->provider !== 'custom' && isset(self::$providerConfigs[$this->provider])) {
            $config = self::$providerConfigs[$this->provider];
            foreach ($config as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }
}
