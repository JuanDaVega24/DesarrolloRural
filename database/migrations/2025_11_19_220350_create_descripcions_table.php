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
        Schema::create('descripcions', function (Blueprint $table) {
            $table->id();

            // Relación con la encuesta
            $table->foreignId('encuesta_id')->constrained('encuestas')->onDelete('cascade');

            // Fuentes de agua (cantidad o texto)
            $table->string('acueducto_publico')->nullable();
            $table->string('acuifero')->nullable();
            $table->string('almacenamiento_aguas_lluvias')->nullable();
            $table->string('aljibes')->nullable();
            $table->string('carrotanque')->nullable();
            $table->string('nacimientos')->nullable();
            $table->string('pila_publica')->nullable();
            $table->string('pozos')->nullable();
            $table->string('red_distribucion_comunitaria')->nullable();
            $table->string('acueducto_veredal')->nullable();
            $table->string('rios')->nullable();
            $table->string('quebradas')->nullable();
            $table->string('otro')->nullable();

            // Producción y herramientas
            $table->string('herramienta_agricola')->nullable();
            $table->string('distancia_finca_cabecera')->nullable();
            $table->string('transporte_cabecera')->nullable();
            $table->string('vias_acceso')->nullable();
            $table->string('condicion_vias')->nullable();

            // Uso del suelo
            $table->decimal('uso_suelo_agricultura', 10, 2)->nullable();
            $table->decimal('uso_suelo_ganaderia', 10, 2)->nullable();
            $table->decimal('uso_suelo_conservacion', 10, 2)->nullable();
            $table->decimal('uso_suelo_casa', 10, 2)->nullable();
            $table->decimal('uso_suelo_rastrojo', 10, 2)->nullable();

            // Almacenamiento
            $table->string('almacen_maquinaria')->nullable();
            $table->string('almacen_insumos_quimicos')->nullable();
            $table->string('almacen_abonos')->nullable();

            // Otros campos
            $table->string('condicion_terreno')->nullable();
            $table->string('sistema_riego')->nullable();
            $table->string('destino_produccion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descripcions');
    }
};
