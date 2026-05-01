<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_name',
        'cnpj',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function workContracts()
    {
        return $this->hasMany(UserWorkContract::class);
    }

    public function dailyPoints()
    {
        return $this->hasMany(DailyPoint::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function pullRequests()
    {
        return $this->hasMany(PullRequest::class);
    }

    public function monthlyReports()
    {
        return $this->hasMany(MonthlyReport::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function emailAccounts()
    {
        return $this->hasMany(EmailAccount::class);
    }

    public function emailMessages()
    {
        return $this->hasMany(EmailMessage::class);
    }

    public function defaultEmailAccount()
    {
        return $this->hasOne(EmailAccount::class)->where('is_default', true);
    }

    public function unreadEmailsCount(): int
    {
        return $this->emailMessages()->where('is_read', false)->count();
    }
}
