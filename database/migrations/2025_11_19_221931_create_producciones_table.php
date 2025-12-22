<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('encuesta_id');
            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');

            /* ======================================
                ACTIVIDADES AGRÍCOLAS
            =======================================*/
            $table->string('tipo_cultivo')->nullable();
            $table->decimal('area_cultivo', 10, 2)->nullable();
            $table->enum('unidad_area_cultivo', ['HA', 'MTS2'])->nullable();
            $table->integer('cantidad_arboles_plantas')->nullable();
            $table->string('nivel_produccion')->nullable();
            $table->string('edades_cultivo')->nullable();
            $table->boolean('seguridad_alimentaria')->nullable();
            $table->boolean('uso_comercial')->nullable();
            $table->boolean('bajo_cubierta')->nullable();
            $table->boolean('cielo_abierto')->nullable();
            $table->boolean('hidroponia')->nullable();

            /* ======================================
                ACTIVIDADES AGROINDUSTRIALES
            =======================================*/
            $table->string('producto_nombre')->nullable();
            $table->boolean('producto_alimentario')->nullable();
            $table->boolean('producto_no_alimentario')->nullable();
            $table->string('producto_presentacion')->nullable();
            $table->decimal('producto_precio', 12, 2)->nullable();
            $table->decimal('producto_capacidad', 12, 2)->nullable();
            $table->boolean('producto_tiene_etiqueta')->nullable();
            $table->boolean('producto_tiene_registro')->nullable();

            /* ======================================
                ACTIVIDADES FORESTALES
            =======================================*/
            $table->string('forestal_modalidad')->nullable();   // disperso, sembrado, plantado
            $table->integer('forestal_cantidad')->nullable();

            /* ======================================
                ACTIVIDAD VIVERO
            =======================================*/
            $table->string('vivero_especies')->nullable();
            $table->integer('vivero_cantidad')->nullable();

            /* ======================================
                PASTOS NATURALES
            =======================================*/
            $table->string('pastos_especies')->nullable();
            $table->decimal('pastos_hectareas', 10, 2)->nullable();
            $table->string('pastos_productos')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producciones');
    }
};


