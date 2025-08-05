import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'brand': {
                    50: '#f0fdf9',
                    100: '#ccfbef',
                    200: '#9df5e0',
                    300: '#5eead4',
                    400: '#2dd4bf',
                    500: '#0ADD90',
                    600: '#00AE6E',
                    700: '#0f766e',
                    800: '#115e59',
                    900: '#134e4a',
                },
                'itti': {
                    'primary': '#0ADD90',
                    'secondary': '#00AE6E',
                    'dark': '#0D0D0D',
                    'dark-purple': '#1F1B24',
                    'gray': '#4A5568',
                    'accent': '#CCEFE2',
                    'cream': '#F8F5F5',
                },
                'teal': {
                    50: '#f0fdf9',
                    100: '#ccfbef',
                    200: '#9df5e0',
                    300: '#5eead4',
                    400: '#2dd4bf',
                    500: '#0ADD90',
                    600: '#00AE6E',
                    700: '#0f766e',
                    800: '#115e59',
                    900: '#134e4a',
                },
            },
            animation: {
                'fade-in': 'fadeIn 0.3s ease-out',
                'slide-up': 'slideUp 0.3s ease-out',
                'bounce-light': 'bounceLight 1s infinite',
            },
        },
    },

    plugins: [forms],
};