<?php

namespace App\Enums;

enum InvoiceType: string
{
    case SERVICE = 'service';
    case PRODUCT = 'product';

    public function label(): string
    {
        return match($this) {
            self::SERVICE => 'Serviço',
            self::PRODUCT => 'Produto',
        };
    }
}
