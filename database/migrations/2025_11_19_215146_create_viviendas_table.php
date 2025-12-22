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
        Schema::create('viviendas', function (Blueprint $table) {
            $table->id();

            // Relación con la encuesta
            $table->unsignedBigInteger('encuesta_id');
            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');

            $table->string('tipo_vivienda')->nullable();
            $table->string('condicion_ocupacion')->nullable();
            $table->string('material_piso')->nullable();
            $table->string('material_pared_exterior')->nullable();
            $table->string('destino_aguas_residuales')->nullable();
            $table->string('combustible_cocina')->nullable();
            $table->string('medios_comunicacion')->nullable();   // puede ser string, JSON o array
            $table->string('medios_electronicos')->nullable();
            $table->boolean('acueducto_veredal')->nullable();
            $table->boolean('cuenta_con_filtro')->nullable();
            $table->string('tipo_servicio_sanitario')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viviendas');
    }
};
