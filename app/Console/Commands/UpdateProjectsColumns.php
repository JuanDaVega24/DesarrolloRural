<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProyectoProductivo;
use App\Http\Controllers\ProyectoProductivoController;

class UpdateProjectsColumns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:update-columns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update automatic columns for all productive projects';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $proyectos = ProyectoProductivo::whereNotNull('data')
            ->where('data->total_rows', '>', 0)
            ->get();

        $this->info("Encontrados {$proyectos->count()} proyectos para actualizar");

        $controller = new ProyectoProductivoController();
        $updated = 0;
        $errors = 0;

        foreach($proyectos as $proyecto) {
            $this->line("Actualizando proyecto: {$proyecto->id} - {$proyecto->nombre}");

            try {
                $result = $controller->updateAutomaticColumns($proyecto);
                if ($result) {
                    $updated++;
                    $this->info("✓ Actualizado correctamente");
                } else {
                    $errors++;
                    $this->error("✗ Error al actualizar");
                }
            } catch (\Exception $e) {
                $errors++;
                $this->error("✗ Excepción: " . $e->getMessage());
            }
        }

        $this->info("Proceso completado: {$updated} actualizados, {$errors} errores");
    }
}
