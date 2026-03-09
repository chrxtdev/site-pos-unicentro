import defaultTheme from 'tailwindcss/defaultTheme'; 
import forms from '@tailwindcss/forms';
import laravel from 'laravel-vite-plugin';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Padrão do Laravel Breeze ou Jetstream
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/@fortawesome/fontawesome-free/css/*.css', // Inclui os estilos do Font Awesome
    ],

    theme: {
        extend: {
            colors: {
                blue: {
                    dark: '#033650',
                    medium: '#0574ac',
                    light: '#84d4f4',
                    vibrant: '#137cb4',
                },
                green: {
                    custom: '#a3b655',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
