import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/finance-charts.js',
                'resources/js/invoice-xml-import.js',
            ],
            refresh: true,
        }),
    ],
});
