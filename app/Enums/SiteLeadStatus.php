<?php

namespace App\Enums;

enum SiteLeadStatus: string
{
    case NEW = 'new';
    case READ = 'read';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Novo',
            self::READ => 'Lido',
            self::ARCHIVED => 'Arquivado',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::NEW => 'blue',
            self::READ => 'gray',
            self::ARCHIVED => 'yellow',
        };
    }
}
