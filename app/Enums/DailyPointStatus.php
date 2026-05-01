<?php

namespace App\Enums;

enum DailyPointStatus: string
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Rascunho',
            self::SENT => 'Enviado',
            self::APPROVED => 'Aprovado',
            self::REJECTED => 'Rejeitado',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'gray',
            self::SENT => 'blue',
            self::APPROVED => 'green',
            self::REJECTED => 'red',
        };
    }
}
