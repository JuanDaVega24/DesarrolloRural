import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/caracterizaciones-table.js',
                'resources/js/caracterizaciones-upload.js',
                'resources/js/beneficiarios-form.js',
                'resources/js/constructor-imagenes.js',
                'resources/js/constructor-opciones-imagenes.js',
                'resources/css/pages/filtros/comparar-proyectos.css',
                'resources/css/pages/filtros/index.css',
                'resources/css/pages/caracterizaciones/formulario.css',
                'resources/css/pages/caracterizaciones/upload-excel.css',
                'resources/css/pages/filtros/validar-proyectos.css',
                 'resources/css/pages/formularios/index.css',
                  'resources/css/pages/proyectos-productivos/create.css',
                 'resources/css/pages/proyectos-productivos/proyectos-por-ano.css',
                'resources/css/pages/formularios/show.css',
                'resources/css/pages/proyectos-productivos/upload_excel.css',
                'resources/css/pages/caracterizaciones/create.css',
                'resources/css/pages/proyectos-productivos/create_manual.css',
                'resources/css/pages/usuarios/edit.css',
                'resources/css/pages/usuarios/index.css',
                'resources/css/pages/formularios/tabla.css',
                'resources/css/pages/reportes/index.css',
                'resources/css/pages/formularios/imagenes.css',
                'resources/css/pages/formularios/opciones-imagenes.css',
                'resources/css/pages/usuarios/create.css',
                'resources/js/formularios-imagenes.js',
                'resources/css/pages/reportes/estadisticas-corregimientos.css',
                'resources/js/formularios-sesiones.js',
                'resources/css/pages/proyectos-productivos/constructor.css',
                'resources/css/pages/proyectos-productivos/index.css',
                'resources/css/pages/proyectos-productivos/show.css',
                'resources/css/pages/reportes/area-proyectos.css',
                'resources/js/constructor-simple.js',
                'resources/css/pages/caracterizaciones/index.css',
                'resources/css/dashboard.css',
                'resources/css/custom-theme.css'
            ],
            refresh: true,
        }),
    ],
});
