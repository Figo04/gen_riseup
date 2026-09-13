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
                    forest: {
                        DEFAULT: '#1F6B53',
                        deep: '#17553F',
                        soft: '#4A8773',
                    },
                    cream: '#F9F6EB',
                    paper: '#FEFDFA',
                    mint: {
                        soft: '#CFEDE0',
                    },
                    pink: {
                        DEFAULT: '#FFE1E3',
                        soft: '#FFE9E6',
                    },
                    lilac: '#ECE8FE',
                    amber: {
                        DEFAULT: '#EEB64F',
                        soft: '#FEEDC9',
                    },
                    ink: '#232E2B',
                    line: '#E3E8DC',
                },
            },
        },
    },

    plugins: [forms],
};
