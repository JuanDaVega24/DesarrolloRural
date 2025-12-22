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
        Schema::create('gestion_agropecuaria', function (Blueprint $table) {
            $table->id();

            $table->foreignId('encuesta_id')->constrained()->cascadeOnDelete();

            $table->string('participa')->nullable();
            $table->integer('año')->nullable();
            $table->string('entidad_gestiono')->nullable();
            $table->text('consistio')->nullable();
            $table->string('credito')->nullable();
            $table->string('aprobado')->nullable();
            $table->string('fuentes')->nullable();
            $table->string('destino_recursos')->nullable();
            $table->string('tiene_creditos')->nullable();
            $table->text('entidad')->nullable();
            $table->text('valor_credito')->nullable();
            $table->text('plazo')->nullable();
            $table->text('fecha_aprobacion')->nullable();
            $table->text('al_dia')->nullable();
            $table->text('seguro')->nullable();
            $table->text('personas')->nullable();
            $table->integer('cuantos')->nullable();
            $table->integer('jornales')->nullable();
            $table->string('trabajo_colectivo')->nullable();
            $table->decimal('valor_jornal', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestion_agropecuaria');
    }
};
