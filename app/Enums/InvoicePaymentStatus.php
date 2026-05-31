<?php

namespace App\Enums;

enum InvoicePaymentStatus: string
{
    case PENDING = 'pending';
    case RECEIVED = 'received';
    case OVERDUE = 'overdue';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::RECEIVED => 'Recebido',
            self::OVERDUE => 'Vencido',
        };
    }
}
