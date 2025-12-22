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
        Schema::create('predio', function (Blueprint $table) {
            $table->id();

            // Relación con encuesta
            $table->foreignId('encuesta_id')->constrained()->cascadeOnDelete();

            // Campos numéricos para uso del suelo
            $table->decimal('uso_agropecuario', 10, 2)->nullable();
            $table->decimal('barbecho', 10, 2)->nullable();
            $table->decimal('descanso', 10, 2)->nullable();
            $table->decimal('rastrojos', 10, 2)->nullable();
            $table->decimal('bosques_naturales', 10, 2)->nullable();
            $table->decimal('construcciones_infraestructura_agropecuaria', 10, 2)->nullable();
            $table->decimal('construcciones_infraestructura_no_agropecuaria', 10, 2)->nullable();
            $table->decimal('otros_usos', 10, 2)->nullable();

            // Campos de ubicación y predio (como JSON en text)
            $table->string('predio_no_continuo')->nullable();
            $table->text('nombre_predio')->nullable(); // JSON
            $table->text('area')->nullable(); // JSON
            $table->text('area2')->nullable(); // JSON
            $table->text('vereda')->nullable(); // JSON
            $table->text('corregimiento')->nullable(); // JSON
            $table->text('municipio')->nullable(); // JSON
            $table->text('departamento')->nullable(); // JSON
            $table->text('tipo_actividad')->nullable(); // JSON
            $table->text('cantidad')->nullable(); // JSON
            $table->text('actividades_no_agropecuarias')->nullable();
            $table->text('actividades')->nullable();
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predio');
    }
};
