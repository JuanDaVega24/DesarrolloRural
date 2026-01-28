<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$proyectos = \App\Models\ProyectoProductivo::whereNotNull('data')->where('data->total_rows', '>', 0)->get();

foreach($proyectos as $proyecto) {
    echo "Actualizando proyecto: {$proyecto->id} - {$proyecto->nombre}\n";
    $controller = new \App\Http\Controllers\ProyectoProductivoController();
    $result = $controller->updateAutomaticColumns($proyecto);
    echo $result ? "Actualizado correctamente\n" : "Error al actualizar\n";
}

echo "Proceso completado\n";
