<?php

if (!function_exists('statusBadge')) {
    /**
     * Retorna classes CSS para badge de status
     *
     * @param string $status
     * @param string $color
     * @return string
     */
    function statusBadge(string $status, string $color): string
    {
        $colors = [
            'gray' => 'bg-gray-100 text-gray-800',
            'blue' => 'bg-blue-100 text-blue-800',
            'green' => 'bg-green-100 text-green-800',
            'red' => 'bg-red-100 text-red-800',
            'yellow' => 'bg-yellow-100 text-yellow-800',
        ];

        return $colors[$color] ?? $colors['gray'];
    }
}
