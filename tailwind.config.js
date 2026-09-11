import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    cream: '#F7F2E6',
                    mint: {
                        DEFAULT: '#7FC79A',
                        light: '#D8EFE0',
                    },
                    peach: {
                        DEFAULT: '#E8A87C',
                        light: '#F7DFC9',
                    },
                    ink: '#1F2A24',
                },
            },
        },
    },

    plugins: [forms],
};
