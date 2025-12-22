<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario_pecuario', function (Blueprint $table) {

            $table->id();
            
            $table->unsignedBigInteger('encuesta_id');
            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');

            // GANADO BOVINO
            $table->boolean('tiene_ganado_bovino')->nullable();
            $table->string('orientacion_ganadera')->nullable();
            $table->string('manejo_alimentacion')->nullable();
            $table->string('vacunas_recibidas')->nullable();
            $table->boolean('pago_biologico')->nullable();

            $table->integer('bovino_machos_menor1')->nullable();
            $table->integer('bovino_machos_1a3')->nullable();
            $table->integer('bovino_machos_mayor3')->nullable();
            $table->integer('bovino_machos_reproductores')->nullable();

            $table->integer('bovino_hembras_menor1')->nullable();
            $table->integer('bovino_hembras_1a3')->nullable();
            $table->integer('bovino_hembras_mayor3')->nullable();
            $table->integer('bovino_hembras_ordeño')->nullable();

            $table->decimal('produccion_leche_litros', 10,2)->nullable();
            $table->string('destino_leche')->nullable();
            $table->string('comercializacion_leche')->nullable();

            // CERDOS
            $table->boolean('tiene_cerdos')->nullable();
            $table->string('orientacion_porcicola')->nullable();
            $table->boolean('vacuna_peste_clasica')->nullable();
            $table->integer('cerdos_machos_reproductores')->nullable();
            $table->integer('cerdos_hembras_gestantes')->nullable();
            $table->integer('cerdos_hembras_reemplazo')->nullable();
            $table->integer('cerdos_descartes')->nullable();
            $table->integer('cerdos_destetos_anio')->nullable();
            $table->integer('cerdos_ceba_anio')->nullable();

            // AVES
            $table->boolean('cria_gallinas_pollos_galpon')->nullable();
            $table->integer('aves_ponedoras')->nullable();
            $table->integer('aves_pollos_engorde')->nullable();
            $table->integer('aves_genetica_huevo')->nullable();
            $table->integer('aves_genetica_engorde')->nullable();
            $table->integer('produccion_huevos_mes')->nullable();
            $table->string('comercializacion_huevos')->nullable();
            $table->decimal('pollo_comercializado_kg_mes', 10,2)->nullable();
            $table->string('donde_comercializo_pollo')->nullable();
            $table->string('metodo_sacrificio')->nullable();
            $table->string('orientacion_avicola')->nullable();

            // PECES
            $table->boolean('cria_peces')->nullable();
            $table->string('peces_especie')->nullable();
            $table->integer('peces_cosechas_anio')->nullable();
            $table->integer('peces_animales_cosecha')->nullable();
            $table->decimal('peces_peso_promedio', 10,2)->nullable();
            $table->decimal('peces_produccion_total_anterior', 10,2)->nullable();
            $table->string('peces_comercializacion')->nullable();
            $table->string('peces_orientacion')->nullable();

            // OTRAS ESPECIES
            $table->boolean('tiene_otros_animales')->nullable();
            $table->integer('caballos')->nullable();
            $table->integer('yeguas')->nullable();
            $table->integer('mulos')->nullable();
            $table->integer('mulas')->nullable();
            $table->integer('burros')->nullable();
            $table->integer('burras')->nullable();
            $table->integer('cabros')->nullable();
            $table->integer('cabras')->nullable();
            $table->integer('ovejos')->nullable();
            $table->integer('ovejas')->nullable();
            $table->integer('bufalos_machos')->nullable();
            $table->integer('bufalos_hembras')->nullable();
            $table->boolean('vacuna_encefalitis_equina')->nullable();
            $table->string('orientacion_ovino_caprina')->nullable();

            // TRASPATIO
            $table->integer('cerdos_traspatio')->nullable();
            $table->integer('gallos_pollos_traspatio')->nullable();
            $table->integer('gallos_pelea')->nullable();
            $table->integer('pavos')->nullable();
            $table->integer('patos_gansos')->nullable();
            $table->integer('codornices')->nullable();
            $table->integer('avestruces')->nullable();
            $table->integer('cuyes')->nullable();
            $table->integer('conejos')->nullable();

            // ABEJAS
            $table->integer('colmenas_miel')->nullable();
            $table->integer('colmenas_polen')->nullable();
            $table->integer('colmenas_subproductos')->nullable();
            $table->integer('colmenas_meliponas')->nullable();

            // MASCOTAS
            $table->integer('caninos_hembras')->nullable();
            $table->integer('caninos_machos')->nullable();
            $table->integer('felinos_hembras')->nullable();
            $table->integer('felinos_machos')->nullable();
            $table->integer('tortugas')->nullable();

            // OTROS
            $table->text('otros2')->nullable();
            $table->boolean('esterilizados')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_pecuario');
    }
};
