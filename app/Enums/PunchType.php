<?php

namespace App\Enums;

enum PunchType: string
{
    case ENTRY = 'entry';
    case LUNCH_OUT = 'lunch_out';
    case LUNCH_RETURN = 'lunch_return';
    case EXIT = 'exit';
    case EXTRA_START = 'extra_start';
    case EXTRA_END = 'extra_end';

    public function label(): string
    {
        return match($this) {
            self::ENTRY => 'Entrada',
            self::LUNCH_OUT => 'Saída Almoço',
            self::LUNCH_RETURN => 'Volta Almoço',
            self::EXIT => 'Saída',
            self::EXTRA_START => 'Início Hora Extra',
            self::EXTRA_END => 'Fim Hora Extra',
        };
    }

    public function fieldName(): string
    {
        return match($this) {
            self::ENTRY => 'entry_time',
            self::LUNCH_OUT => 'lunch_out_time',
            self::LUNCH_RETURN => 'lunch_return_time',
            self::EXIT => 'exit_time',
            self::EXTRA_START => 'extra_start_time',
            self::EXTRA_END => 'extra_end_time',
        };
    }

    public function order(): int
    {
        return match($this) {
            self::ENTRY => 1,
            self::LUNCH_OUT => 2,
            self::LUNCH_RETURN => 3,
            self::EXIT => 4,
            self::EXTRA_START => 5,
            self::EXTRA_END => 6,
        };
    }
}
