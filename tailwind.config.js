import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito Sans', ...defaultTheme.fontFamily.sans],
                verdana: ['Verdana', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'verde': '#4A7C2F',
                'verde-hover': '#3d6625',
                'verde-claro': '#E8F5E0',
                'azul': '#3366CC',
                'azul-hover': '#2952a3',
                'azul-claro': '#E3ECFA',
                'negro': '#1A1A1A',
                'gris': '#666666',
                'gris-claro': '#f8f9fa',
                'gris-medio': '#e9ecef',
                'beige': '#F8F6F3',
                'blanco': '#FFFFFF',
            },
        },
    },

    plugins: [forms, typography],
};
