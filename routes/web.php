<?php

use App\Models\User;
use App\Http\Controllers\DescripcionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EncuestaController;
use App\Http\Controllers\ProyectoProductivoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ViviendaController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\FinalEncuestaController;
use App\Http\Controllers\InventarioPecuarioController;
use App\Http\Controllers\GestionAgropecuariaController;
use App\Http\Controllers\PredioController;
use App\Http\Controllers\ControlActividadeController;
use App\Http\Controllers\FamiliarController;
use App\Http\Controllers\AfectacionController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\EncuestadorController;
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
        | PASO 1 – DATOS PERSONALES
        |----------------------------------------------------------------------
        */
        Route::get('encuestas/datos-personales', [EncuestaController::class, 'datosPersonales'])
            ->name('encuestas.datos_personales');

        Route::post('encuestas/datos-personales', [EncuestaController::class, 'guardarDatosPersonales'])
            ->name('encuestas.guardarDatosPersonales');


        /*
        |----------------------------------------------------------------------
        | PASO 2 – VIVIENDA
        |----------------------------------------------------------------------
        */
        Route::prefix('encuestas')->group(function () {

            Route::get('/vivienda', [ViviendaController::class, 'create'])
                ->name('encuestas.vivienda');

            Route::post('/vivienda', [ViviendaController::class, 'guardarVivienda'])
                ->name('vivienda.guardarVivienda');

            // Rutas para show/edit de vivienda individual
            Route::get('/vivienda/{vivienda}', [ViviendaController::class, 'show'])
                ->name('viviendas.show');

            Route::get('/vivienda/{vivienda}/edit', [ViviendaController::class, 'edit'])
                ->name('viviendas.edit');

            Route::put('/vivienda/{vivienda}', [ViviendaController::class, 'update'])
                ->name('viviendas.update');

            Route::delete('/vivienda/{vivienda}', [ViviendaController::class, 'destroy'])
                ->name('viviendas.destroy');

            Route::get('/descripcion', [DescripcionController::class, 'create'])
                ->name('encuestas.descripcion');

            Route::post('/descripcion', [DescripcionController::class, 'guardarDescripcion'])
                ->name('descripcion.guardarDescripcion');

            // Rutas para show/edit de descripciones individuales
            Route::get('/descripcion/{descripcion}', [DescripcionController::class, 'show'])
                ->name('descripciones.show');

            Route::get('/descripcion/{descripcion}/edit', [DescripcionController::class, 'edit'])
                ->name('descripciones.edit');

            Route::put('/descripcion/{descripcion}', [DescripcionController::class, 'update'])
                ->name('descripciones.update');

            Route::delete('/descripcion/{descripcion}', [DescripcionController::class, 'destroy'])
                ->name('descripciones.destroy');

            Route::get('/produccion', [ProduccionController::class, 'create'])
                ->name('encuestas.produccion');

            Route::post('/produccion', [ProduccionController::class, 'guardarProduccion'])
                ->name('produccion.guardarProduccion');

            // Rutas para show/edit de producciones individuales
            Route::get('/produccion/{produccion}', [ProduccionController::class, 'show'])
                ->name('producciones.show');

            Route::get('/produccion/{produccion}/edit', [ProduccionController::class, 'edit'])
                ->name('producciones.edit');

            Route::put('/produccion/{produccion}', [ProduccionController::class, 'update'])
                ->name('producciones.update');

            Route::delete('/produccion/{produccion}', [ProduccionController::class, 'destroy'])
                ->name('producciones.destroy');

            Route::get('/maquinaria', [MaquinariaController::class, 'create'])
                ->name('encuestas.maquinaria');

            Route::post('/maquinaria', [MaquinariaController::class, 'guardarMaquinaria'])
                ->name('maquinaria.guardarMaquinaria');

            // Rutas para show/edit de maquinaria individuales
            Route::get('/maquinaria/{maquinaria}', [MaquinariaController::class, 'show'])
                ->name('maquinaria.show');

            Route::get('/maquinaria/{maquinaria}/edit', [MaquinariaController::class, 'edit'])
                ->name('maquinaria.edit');

            Route::put('/maquinaria/{maquinaria}', [MaquinariaController::class, 'update'])
                ->name('maquinaria.update');

            Route::delete('/maquinaria/{maquinaria}', [MaquinariaController::class, 'destroy'])
                ->name('maquinaria.destroy');

            Route::get('/gestion_agropecuaria', [GestionAgropecuariaController::class, 'create'])
                ->name('encuestas.gestion_agropecuaria');

            Route::post('/gestion_agropecuaria', [GestionAgropecuariaController::class, 'guardarGestionAgropecuaria'])
                ->name('gestion_agropecuaria.guardarGestionAgropecuaria');

            // Rutas para show/edit de gestion agropecuaria individuales
            Route::get('/gestion_agropecuaria/{gestion}', [GestionAgropecuariaController::class, 'show'])
                ->name('gestion_agropecuaria.show');

            Route::get('/gestion_agropecuaria/{gestion}/edit', [GestionAgropecuariaController::class, 'edit'])
                ->name('gestion_agropecuaria.edit');

            Route::put('/gestion_agropecuaria/{gestion}', [GestionAgropecuariaController::class, 'update'])
                ->name('gestion_agropecuaria.update');

            Route::delete('/gestion_agropecuaria/{gestion}', [GestionAgropecuariaController::class, 'destroy'])
                ->name('gestion_agropecuaria.destroy');

            Route::get('/inventario_pecuario', [InventarioPecuarioController::class, 'create'])
                ->name('encuestas.inventario_pecuarios');

            Route::post('/inventario_pecuario', [InventarioPecuarioController::class, 'guardarPecuario'])
                ->name('inventario_pecuario.guardarPecuario');

            // Rutas para show/edit de inventario pecuario individuales
            Route::get('/inventario_pecuario/{inventario_pecuario}', [InventarioPecuarioController::class, 'show'])
                ->name('inventario_pecuario.show');

            Route::get('/inventario_pecuario/{inventario_pecuario}/edit', [InventarioPecuarioController::class, 'edit'])
                ->name('inventario_pecuario.edit');

            Route::put('/inventario_pecuario/{inventario_pecuario}', [InventarioPecuarioController::class, 'update'])
                ->name('inventario_pecuario.update');

            Route::get('/predio', [PredioController::class, 'create'])
                ->name('encuestas.predio');

            Route::post('/predio', [PredioController::class, 'guardarPredio'])
                ->name('predio.guardarPredio');

            // Rutas para show/edit de predio individuales
            Route::get('/predio/{predio}', [PredioController::class, 'show'])
                ->name('predio.show');

            Route::get('/predio/{predio}/edit', [PredioController::class, 'edit'])
                ->name('predio.edit');

            Route::put('/predio/{predio}', [PredioController::class, 'update'])
                ->name('predio.update');

            Route::delete('/predio/{predio}', [PredioController::class, 'destroy'])
                ->name('predio.destroy');

            Route::get('/control_actividades', [ControlActividadeController::class, 'create'])
                ->name('encuestas.control_actividades');

            Route::post('/control_actividades', [ControlActividadeController::class, 'guardarControlActividade'])
                ->name('control_actividades.guardarControlActividade');

            // Rutas para show/edit de control actividades individuales
            Route::get('/control_actividades/{controlActividade}', [ControlActividadeController::class, 'show'])
                ->name('control_actividades.show');

            Route::get('/control_actividades/{controlActividade}/edit', [ControlActividadeController::class, 'edit'])
                ->name('control_actividades.edit');

            Route::put('/control_actividades/{controlActividade}', [ControlActividadeController::class, 'update'])
                ->name('control_actividades.update');

            Route::delete('/control_actividades/{controlActividade}', [ControlActividadeController::class, 'destroy'])
                ->name('control_actividades.destroy');

            Route::get('/familiares', [FamiliarController::class, 'create'])
                ->name('encuestas.familiares');

            Route::post('/familiares', [FamiliarController::class, 'guardarFamiliares'])
                ->name('familiares.guardarFamiliares');

            // Rutas para show/edit de familiares
            Route::get('/familiares/{encuesta}', [FamiliarController::class, 'show'])
                ->name('familiares.show');

            Route::get('/familiares/{encuesta}/edit', [FamiliarController::class, 'edit'])
                ->name('familiares.edit');

            Route::delete('/familiares/{encuesta}', [FamiliarController::class, 'destroy'])
                ->name('familiares.destroy');

            Route::get('/afectaciones', [AfectacionController::class, 'create'])
                ->name('encuestas.afectaciones');

            Route::post('/afectaciones', [AfectacionController::class, 'guardarAfectaciones'])
                ->name('afectaciones.guardarAfectaciones');

            // Rutas para show/edit de afectaciones
            Route::get('/afectaciones/{encuesta}', [AfectacionController::class, 'show'])
                ->name('afectaciones.show');

            Route::get('/afectaciones/{encuesta}/edit', [AfectacionController::class, 'edit'])
                ->name('afectaciones.edit');

            Route::delete('/afectaciones/{encuesta}', [AfectacionController::class, 'destroy'])
                ->name('afectaciones.destroy');

            Route::get('/veredas/{id}', [EncuestaController::class, 'getVeredas'])
    ->name('encuestas.getVeredas');

            Route::post('/establecer-sesion/{encuestaId}', [EncuestaController::class, 'establecerSesion'])
    ->name('encuestas.establecerSesion');

            Route::post('/establecer-sesion-show/{encuestaId}', [EncuestaController::class, 'establecerSesionShow'])
    ->name('encuestas.establecerSesionShow');

            /*
            |----------------------------------------------------------------------
            | PASO FINAL – INFORMACIÓN DEL ENCUESTADOR
            |----------------------------------------------------------------------
            */
            Route::get('/encuestador/{encuesta}', [EncuestadorController::class, 'show'])
                ->name('encuestador.show');

            Route::get('/final', [EncuestadorController::class, 'create'])
                ->name('encuestador.create');

            Route::post('/final', [EncuestadorController::class, 'store'])
                ->name('encuestador.store');

            Route::delete('/encuestador/{encuesta}', [EncuestadorController::class, 'destroy'])
                ->name('encuestador.destroy');

            });

        Route::resource('encuestas', EncuestaController::class);

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

        Route::post('reportes/filtrar', [ReporteController::class,'filtrar'])
            ->name('reportes.filtrar');

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

        // API para datos de estadísticas (si se necesita AJAX)
        Route::get('api/reportes/estadisticas-corregimientos', [ReporteController::class,'estadisticasCorregimientos'])
            ->name('api.reportes.estadisticas-corregimientos');

        // Área por corregimientos para proyectos productivos
        Route::get('reportes/area-proyectos', [ReporteController::class,'areaProyectosView'])
            ->name('reportes.area-proyectos');

        Route::get('reportes/area-proyectos/pdf', [ReporteController::class,'areaProyectosPDF'])
            ->name('reportes.area-proyectos.pdf');

        Route::get('reportes/area-proyectos/vereda/pdf', [ReporteController::class,'areaProyectosVeredaPDF'])
            ->name('reportes.area-proyectos.vereda.pdf');

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
