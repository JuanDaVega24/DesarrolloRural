<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProyectoProductivoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CaracterizacionController;
use App\Http\Controllers\CaracterizacionFormularioController;
use App\Http\Controllers\FormularioController;

Route::get('/', function () {
    return view('auth/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    |  SOLO ADMINISTRADOR
    |--------------------------------------------------------------------------
    |
    |  Todo lo exclusivo como CRUD de usuarios
    |
    */
    Route::middleware('admin')->group(function () {

        // ⛔ EJEMPLO: CRUD de usuarios (solo admin)
         Route::resource('usuarios', AdminUserController::class);
    });



    /*
    |--------------------------------------------------------------------------
    |  ADMINISTRADOR + TABULADOR
    |--------------------------------------------------------------------------
    |
    |  Todo el módulo de encuestas completo
    |
    */
    Route::middleware('adminOrTabulador')->group(function () {

       

        /*
        |----------------------------------------------------------------------
        | PROYECTOS PRODUCTIVOS
        |----------------------------------------------------------------------
        */
        // Rutas específicas que deben ir ANTES del resource para evitar conflictos
        Route::get('proyectos/create-manual', [ProyectoProductivoController::class, 'createManual'])
            ->name('proyectos.create-manual');

        Route::post('proyectos/store-manual', [ProyectoProductivoController::class, 'storeManual'])
            ->name('proyectos.store-manual');

        // Ruta para mostrar proyectos por año
        Route::get('proyectos/ano/{ano}', [ProyectoProductivoController::class, 'proyectosPorAno'])
            ->name('proyectos.por-ano');

        // Ruta resource (debe ir DESPUÉS de las rutas específicas)
        Route::resource('proyectos', ProyectoProductivoController::class);

        // Rutas adicionales para proyectos productivos (con parámetros específicos)
        Route::get('proyectos/{proyecto}/upload-excel', [ProyectoProductivoController::class, 'uploadExcel'])
            ->name('proyectos.upload-excel');

        Route::post('proyectos/{proyecto}/upload-excel', [ProyectoProductivoController::class, 'processExcel'])
            ->name('proyectos.process-excel');

        Route::get('proyectos/{proyecto}/show', [ProyectoProductivoController::class, 'show'])
            ->name('proyectos.show');

        Route::get('proyectos/{proyecto}/export-excel', [ProyectoProductivoController::class, 'exportExcel'])
            ->name('proyectos.export-excel');

        // Ruta para actualizar columnas automáticas de un proyecto
        Route::post('proyectos/{proyecto}/update-automatic-columns', [ProyectoProductivoController::class, 'updateAutomaticColumnsPost'])
            ->name('proyectos.update-automatic-columns');

        // Ruta para actualizar columnas automáticas de todos los proyectos
        Route::post('proyectos/update-all-automatic-columns', [ProyectoProductivoController::class, 'updateAllProjectsColumns'])
            ->name('proyectos.update-all-automatic-columns');

        /*
        |----------------------------------------------------------------------
        | FORMULARIOS DE PROYECTOS
        |----------------------------------------------------------------------
        */

        // Ruta para formulario de caracterizaciones
        Route::get('caracterizaciones/formulario', [CaracterizacionFormularioController::class, 'show'])
            ->name('caracterizaciones.formulario.show');

        Route::put('caracterizaciones/formulario', [CaracterizacionFormularioController::class, 'update'])
            ->name('caracterizaciones.formulario.update');

        // AJAX route para validar cédula en caracterizaciones
        Route::post('caracterizaciones/formulario/validar-cedula', [CaracterizacionFormularioController::class, 'validarCedula'])
            ->name('caracterizaciones.formulario.validar-cedula');

        /*
        |----------------------------------------------------------------------
        | FORMULARIOS DE PROYECTOS
        |----------------------------------------------------------------------
        */
        Route::get('formularios', [FormularioController::class, 'index'])
            ->name('formularios.index');

        Route::get('formularios/{proyecto}', [FormularioController::class, 'show'])
            ->name('formularios.show');

        Route::put('formularios/{proyecto}', [FormularioController::class, 'update'])
            ->name('formularios.update');

        Route::delete('formularios/{proyecto}', [FormularioController::class, 'destroy'])
            ->name('formularios.destroy');

        // AJAX route para validar cédula
        Route::post('formularios/validar-cedula', [FormularioController::class, 'validarCedula'])
            ->name('formularios.validar-cedula');

        /*
        |----------------------------------------------------------------------
        | CARACTERIZACIONES
        |----------------------------------------------------------------------
        */
        Route::resource('caracterizaciones', CaracterizacionController::class);

        // Rutas adicionales para caracterizaciones
        Route::get('caracterizaciones/{caracterizacion}/upload-excel', [CaracterizacionController::class, 'uploadExcel'])
            ->name('caracterizaciones.upload-excel');

        Route::post('caracterizaciones/{caracterizacion}/upload-excel', [CaracterizacionController::class, 'processExcel'])
            ->name('caracterizaciones.process-excel');

        Route::get('caracterizaciones/{caracterizacion}/show', [CaracterizacionController::class, 'show'])
            ->name('caracterizaciones.show');

        Route::get('caracterizaciones/{caracterizacion}/export-excel', [CaracterizacionController::class, 'exportExcel'])
            ->name('caracterizaciones.export-excel');

        // Ruta para mostrar caracterizaciones por año
        Route::get('caracterizaciones/ano/{ano}', [CaracterizacionController::class, 'caracterizacionesPorAno'])
            ->name('caracterizaciones.por-ano');

        // Ruta AJAX para filtros dinámicos
        Route::get('caracterizaciones/ajax/filter-data', [CaracterizacionController::class, 'ajaxFilterData'])
            ->name('caracterizaciones.ajax.filter-data');

        /*
        |----------------------------------------------------------------------
        | REPORTES
        |----------------------------------------------------------------------
        */
        Route::get('reportes', [ReporteController::class,'index'])
            ->name('reportes.index');

        // Estadísticas de corregimientos
        Route::get('reportes/estadisticas-corregimientos', [ReporteController::class,'estadisticasCorregimientosView'])
            ->name('reportes.estadisticas-corregimientos');

        Route::get('reportes/estadisticas-corregimientos/pdf', [ReporteController::class,'estadisticasCorregimientosPDF'])
            ->name('reportes.estadisticas-corregimientos.pdf');

        Route::get('reportes/estadisticas-genero/pdf', [ReporteController::class,'estadisticasGeneroPDF'])
            ->name('reportes.estadisticas-genero.pdf');

        Route::get('reportes/estadisticas-edad/pdf', [ReporteController::class,'estadisticasEdadPDF'])
            ->name('reportes.estadisticas-edad.pdf');

        Route::get('reportes/estadisticas-area/pdf', [ReporteController::class,'estadisticasAreaPDF'])
            ->name('reportes.estadisticas-area.pdf');
        
        Route::get('reportes/area-proyectos', [ReporteController::class,'areaProyectosView'])
            ->name('reportes.area-proyectos');
        Route::get('reportes/area-proyectos/pdf', [ReporteController::class,'areaProyectosPDF'])
            ->name('reportes.area-proyectos.pdf');
        Route::get('reportes/area-proyectos/vereda/pdf', [ReporteController::class,'areaProyectosVeredaPDF'])
            ->name('reportes.area-proyectos.vereda.pdf');

        // API para datos de estadísticas (si se necesita AJAX)
        Route::get('api/reportes/estadisticas-corregimientos', [ReporteController::class,'estadisticasCorregimientos'])
            ->name('api.reportes.estadisticas-corregimientos');

        /*
        |----------------------------------------------------------------------
        | FILTROS Y BÚSQUEDAS
        |----------------------------------------------------------------------
        */
        Route::get('filtros', function () {
            return view('filtros.index');
        })->name('filtros.index');

        Route::get('filtros/validar-proyectos', function () {
            return view('filtros.validar-proyectos');
        })->name('filtros.validar-proyectos');

        Route::get('filtros/comparar-proyectos', function () {
            return view('filtros.comparar-proyectos');
        })->name('filtros.comparar-proyectos');

        // Ruta para cultivos por corregimiento
        Route::get('filtros/cultivos-corregimiento', [ReporteController::class, 'cultivosPorCorregimiento'])
            ->name('filtros.cultivos-corregimiento');

        // API routes for validation
        Route::get('api/proyectos-excel', [ProyectoProductivoController::class, 'getProyectosExcel']);
        Route::get('api/anios-disponibles', [ProyectoProductivoController::class, 'getAniosDisponibles']);
        Route::get('api/proyectos/{proyecto}/validar', [ProyectoProductivoController::class, 'validarProyecto']);

        // API routes for project comparison
        Route::get('api/proyectos-comparar', [ProyectoProductivoController::class, 'getProyectosParaComparar']);
        Route::get('api/proyectos/{proyecto}/comparar', [ProyectoProductivoController::class, 'compararProyecto']);

/*
|--------------------------------------------------------------------------
| RUTA PARA CARGAR VEREDAS SEGÚN CORREGIMIENTO (AJAX)
|--------------------------------------------------------------------------
*/



    });
});
