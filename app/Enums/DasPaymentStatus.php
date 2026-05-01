<?php

namespace App\Enums;

enum DasPaymentStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case OVERDUE = 'overdue';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pendente',
            self::PAID => 'Pago',
            self::OVERDUE => 'Vencido',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::PAID => 'green',
            self::OVERDUE => 'red',
        };
    }
}
