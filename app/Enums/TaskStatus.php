<?php

namespace App\Enums;

enum TaskStatus: string
{
    case TODO = 'todo';
    case DOING = 'doing';
    case DONE = 'done';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::TODO => 'A Fazer',
            self::DOING => 'Em Andamento',
            self::DONE => 'Concluída',
            self::CANCELLED => 'Cancelada',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::TODO => 'gray',
            self::DOING => 'yellow',
            self::DONE => 'green',
            self::CANCELLED => 'red',
        };
    }
}
