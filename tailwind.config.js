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
                speedweek: '#d0362e',
            },
            fontFamily: {
                sans: ['Play', ...defaultTheme.fontFamily.sans],
                display: ['Play', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
