import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
            },
            colors: {
                primary: {
                    50: 'var(--color-primary-50)',
                    100: 'var(--color-primary-100)',
                    200: 'var(--color-primary-200)',
                    300: 'var(--color-primary-300)',
                    400: 'var(--color-primary-400)',
                    500: 'var(--color-primary-500)',
                    600: 'var(--color-primary-600)',
                    700: 'var(--color-primary-700)',
                    800: 'var(--color-primary-800)',
                    900: 'var(--color-primary-900)',
                    950: 'var(--color-primary-950)',
                },
                accent: {
                    50: 'var(--color-accent-50)',
                    100: 'var(--color-accent-100)',
                    200: 'var(--color-accent-200)',
                    300: 'var(--color-accent-300)',
                    400: 'var(--color-accent-400)',
                    500: 'var(--color-accent-500)',
                    600: 'var(--color-accent-600)',
                    700: 'var(--color-accent-700)',
                    800: 'var(--color-accent-800)',
                    900: 'var(--color-accent-900)',
                    950: 'var(--color-accent-950)',
                },
                surface: {
                    50: 'var(--color-surface-50)',
                    100: 'var(--color-surface-100)',
                    200: 'var(--color-surface-200)',
                    300: 'var(--color-surface-300)',
                    400: 'var(--color-surface-400)',
                    500: 'var(--color-surface-500)',
                    600: 'var(--color-surface-600)',
                    700: 'var(--color-surface-700)',
                    800: 'var(--color-surface-800)',
                    900: 'var(--color-surface-900)',
                    950: 'var(--color-surface-950)',
                },
            },
        },
    },

    plugins: [forms],
};
