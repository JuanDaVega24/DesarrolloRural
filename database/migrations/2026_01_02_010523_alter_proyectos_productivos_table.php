<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('proyectos_productivos', function (Blueprint $table) {
            $table->dropColumn(['sector', 'costo_estimado', 'fecha_inicio', 'fecha_fin', 'estado']);
            $table->enum('estado', ['Activo', 'Inactivo', 'Finalizado'])->default('Activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyectos_productivos', function (Blueprint $table) {
            $table->dropColumn('estado');
            $table->string('sector'); // agrícola, textil, pecuario, etc.
            $table->decimal('costo_estimado', 12, 2)->nullable();
            $table->enum('estado', ['Propuesto', 'En ejecución', 'Finalizado'])->default('Propuesto');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
        });
    }
};
