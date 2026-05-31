<?php

if (! function_exists('parse_brazilian_money')) {
    /**
     * Converte valor monetário BR (R$ 1.234,56) ou numérico (1234.56) para float.
     */
    function parse_brazilian_money(mixed $value): ?float
    {
        return parse_brazilian_decimal($value);
    }
}

if (! function_exists('parse_brazilian_decimal')) {
    /**
     * Converte número no formato brasileiro (1.234,56) ou ponto decimal (1234.56) para float.
     */
    function parse_brazilian_decimal(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        if (! is_string($value)) {
            return null;
        }

        $str = trim(str_replace(["\xc2\xa0", 'R$', ' '], '', $value));

        if ($str === '') {
            return null;
        }

        if (str_contains($str, ',')) {
            $str = str_replace('.', '', $str);
            $str = str_replace(',', '.', $str);
        }

        if (! is_numeric($str)) {
            return null;
        }

        return (float) $str;
    }
}

if (! function_exists('format_brazilian_money')) {
    function format_brazilian_money(mixed $value): string
    {
        $parsed = parse_brazilian_money($value);

        if ($parsed === null) {
            return '';
        }

        return 'R$ '.number_format($parsed, 2, ',', '.');
    }
}
