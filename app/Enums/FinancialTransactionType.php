<?php

namespace App\Enums;

enum FinancialTransactionType: string
{
    case RECEIVABLE = 'receivable';
    case PAYABLE = 'payable';

    public function label(): string
    {
        return match ($this) {
            self::RECEIVABLE => 'A receber',
            self::PAYABLE => 'A pagar',
        };
    }
}
