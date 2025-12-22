<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maquinaria', function (Blueprint $table) {
            $table->id();

            // Relación con encuesta
            $table->unsignedBigInteger('encuesta_id');
            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');

            // Maquinaria agropecuaria: tipo, cantidad, antigüedad y estado
            $table->text('maquinaria')->nullable(); 
            // Puede guardarse como JSON o texto, por ejemplo:

            // Construcción
            $table->boolean('tiene_construccion')->nullable();

            // Asociación
            $table->boolean('pertenece_asociacion')->nullable();
            $table->string('nombre_asociacion')->nullable(); // Si selecciona Otras

            // Asistencia/asesoría
            $table->string('entidad_asesoria')->nullable();
            $table->boolean('recibio_asesoria_ultimo_anio')->nullable();

            // Temas y si pagó
            $table->boolean('tema_buenas_practicas_agricolas')->nullable();
            $table->boolean('pago_bpa')->nullable();

            $table->boolean('tema_buenas_practicas_pecuarias')->nullable();
            $table->boolean('pago_bpp')->nullable();

            $table->boolean('tema_manejo_ambiental')->nullable();
            $table->boolean('pago_ma')->nullable();

            $table->boolean('tema_manejo_suelos')->nullable();
            $table->boolean('pago_ms')->nullable();

            $table->boolean('tema_manejo_postcosecha')->nullable();
            $table->boolean('pago_mpc')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maquinaria');
    }
};
