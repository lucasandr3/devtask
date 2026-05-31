<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case ACTIVE = 'active';
    case PAUSED = 'paused';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Ativo',
            self::PAUSED => 'Pausado',
            self::COMPLETED => 'Concluído',
            self::CANCELLED => 'Cancelado',
        };
    }
}
