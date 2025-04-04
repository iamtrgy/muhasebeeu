import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    safelist: [
        'from-blue-500',
        'from-emerald-500',
        'from-purple-500',
        'to-blue-600',
        'to-emerald-600',
        'to-purple-600',
        'text-blue-100',
        'text-emerald-100',
        'text-purple-100',
    ],

    plugins: [forms],
};
