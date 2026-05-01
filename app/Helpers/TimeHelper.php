<?php

if (!function_exists('minutesToHours')) {
    /**
     * Converte minutos para formato "hh:mm"
     *
     * @param int|null $minutes
     * @return string
     */
    function minutesToHours(?int $minutes): string
    {
        if ($minutes === null) {
            return '00:00';
        }

        $negative = $minutes < 0;
        $minutes = abs($minutes);
        $hours = intval($minutes / 60);
        $mins = $minutes % 60;
        $formatted = sprintf('%02d:%02d', $hours, $mins);

        return $negative ? '-' . $formatted : $formatted;
    }
}

if (!function_exists('hoursToMinutes')) {
    /**
     * Converte formato "hh:mm" para minutos
     *
     * @param string $hours
     * @return int
     */
    function hoursToMinutes(string $hours): int
    {
        [$h, $m] = explode(':', $hours);
        return (intval($h) * 60) + intval($m);
    }
}
