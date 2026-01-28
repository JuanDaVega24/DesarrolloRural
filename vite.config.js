import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/caracterizaciones-table.js',
                'resources/css/pages/filtros/comparar-proyectos.css',
                'resources/css/pages/filtros/index.css',
                'resources/css/pages/filtros/validar-proyectos.css',
            ],
            refresh: true,
        }),
    ],
});
