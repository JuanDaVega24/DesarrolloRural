<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('proyectos_productivos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->text('descripcion')->nullable();
        $table->string('sector'); // agrícola, textil, pecuario, etc.
        $table->decimal('costo_estimado', 12, 2)->nullable();
        $table->enum('estado', ['Propuesto', 'En ejecución', 'Finalizado'])->default('Propuesto');
        $table->date('fecha_inicio')->nullable();
        $table->date('fecha_fin')->nullable();

      

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Usar CASCADE para PostgreSQL para eliminar dependencias (como tablas de preguntas o caracterizaciones)
        DB::statement('DROP TABLE IF EXISTS proyectos_productivos CASCADE');
    }
};
