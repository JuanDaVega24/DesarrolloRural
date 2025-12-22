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

            Route::get('/afectaciones', [AfectacionController::class, 'create'])
                ->name('encuestas.afectaciones');

            Route::post('/afectaciones', [AfectacionController::class, 'guardarAfectaciones'])
                ->name('afectaciones.guardarAfectaciones');

            // Rutas para show/edit de afectaciones
            Route::get('/afectaciones/{encuesta}', [AfectacionController::class, 'show'])
                ->name('afectaciones.show');

            Route::get('/afectaciones/{encuesta}/edit', [AfectacionController::class, 'edit'])
                ->name('afectaciones.edit');

            Route::get('/veredas/{id}', [EncuestaController::class, 'getVeredas'])
    ->name('encuestas.getVeredas');

            Route::post('/establecer-sesion/{encuestaId}', [EncuestaController::class, 'establecerSesion'])
    ->name('encuestas.establecerSesion');


       
       
            });

        Route::resource('encuestas', EncuestaController::class);

        /*
        |----------------------------------------------------------------------
        | PROYECTOS PRODUCTIVOS
        |----------------------------------------------------------------------
        */
        Route::resource('proyectos', ProyectoProductivoController::class);

        /*
        |----------------------------------------------------------------------
        | REPORTES
        |----------------------------------------------------------------------
        */
        Route::get('reportes', [ReporteController::class,'index'])
            ->name('reportes.index');

        Route::post('reportes/filtrar', [ReporteController::class,'filtrar'])
            ->name('reportes.filtrar');
/*
|--------------------------------------------------------------------------
| RUTA PARA CARGAR VEREDAS SEGÚN CORREGIMIENTO (AJAX)
|--------------------------------------------------------------------------
*/



    });
});
