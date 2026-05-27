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
            colors: {
                speedweek: {
                    50: '#fdf3f2',
                    100: '#fbe3e1',
                    200: '#f6cbc7',
                    300: '#efa8a1',
                    400: '#e57970',
                    500: '#d0362e',
                    600: '#bd3029',
                    700: '#9d2823',
                    800: '#81231f',
                    900: '#6c211e',
                    950: '#3b0e0c',
                },
            },
            fontFamily: {
                sans: ['Play', ...defaultTheme.fontFamily.sans],
                display: ['Play', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
