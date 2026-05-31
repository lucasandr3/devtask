<?php

namespace App\Enums;

enum CompanyRole: string
{
    case ADMIN = 'admin';
    case LEADER = 'leader';
    case MEMBER = 'member';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::LEADER => 'Líder',
            self::MEMBER => 'Membro',
        };
    }

    public function canManageProjects(): bool
    {
        return in_array($this, [self::ADMIN, self::LEADER], true);
    }

    public function canManageTeam(): bool
    {
        return $this === self::ADMIN;
    }

    public function canViewCompanyReports(): bool
    {
        return in_array($this, [self::ADMIN, self::LEADER], true);
    }

    public function canViewFinance(): bool
    {
        return in_array($this, [self::ADMIN, self::LEADER], true);
    }

    public function canManageFinance(): bool
    {
        return $this === self::ADMIN;
    }

    public function canApproveReports(): bool
    {
        return in_array($this, [self::ADMIN, self::LEADER], true);
    }

    public function isMember(): bool
    {
        return $this === self::MEMBER;
    }
}
