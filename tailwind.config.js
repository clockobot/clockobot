import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: ['class'],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/css/app.css',
    ],
    safelist: [
        'bg-green-500',
        'bg-red-400',
        'bg-orange-500',
        {
            pattern: /max-w-(sm|md|lg|xl|2xl|3xl|4xl|5xl|6xl|7xl)/,
            variants: ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'],
        },
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                app: {
                    DEFAULT: '#4FC1B5',
                    'contrast': '#3d978d',
                },
                night: {
                    '1': '#D6EDFF',
                    '2': '#BBDBF4',
                    '3': '#98BDD7',
                    '4': '#7693A6',
                    '5': '#4C6473',
                    '6': '#3B4E59',
                    '7': '#202B35',
                    '8': '#171F26',
                },
                day: {
                    '1': '#F6F6F6',
                    '2': '#EFEFEF',
                    '3': '#DFDFDF',
                    '4': '#CFCFCF',
                    '5': '#BFBFBF',
                    '6': '#959595',
                    '7': '#212126',
                    '8': '#0C0C0E',
                }
            },
        },
    },

    plugins: [forms],
};
