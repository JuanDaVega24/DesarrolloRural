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
        Schema::create('control_actividades', function (Blueprint $table) {
            $table->id();

            // Relación con encuesta
            $table->foreignId('encuesta_id')->constrained()->cascadeOnDelete();

            // Campos de control de actividades
            $table->string('unidad_productiva')->nullable();
            $table->string('cuales')->nullable();
            $table->string('fertilizantes')->nullable();
            $table->string('tipo_fertilizantes')->nullable();
            $table->string('frecuencia_aplicacion')->nullable();
            $table->string('mecanismos')->nullable();
            $table->string('analisis')->nullable();
            $table->string('analisis_ayuda')->nullable();
            $table->integer('fecha_analisis')->nullable();
            $table->string('control')->nullable();
            $table->string('frecuencia')->nullable();
            $table->string('control_plagas')->nullable();
            $table->string('tipo_control')->nullable();
            $table->string('conoce_BPA')->nullable();
            $table->string('conoce_inocuidad')->nullable();
            $table->string('desinfectar')->nullable();
            $table->string('toxicidad')->nullable();
            $table->string('proteccion')->nullable();
            $table->string('cuales_proteccion')->nullable();
            $table->string('plaguicidas')->nullable();
            $table->string('tiempo_plaguicida')->nullable();
            $table->string('cultivo_plaguicida')->nullable();
            $table->string('envases_plaguicida')->nullable();
            $table->string('calidad_predio')->nullable();
            $table->string('analisis_agua')->nullable();
            $table->string('cual_analisis')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_actividades');
    }
};
