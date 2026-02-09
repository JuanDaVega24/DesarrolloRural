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
        Schema::dropIfExists('encuestas');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('encuestas', function (Blueprint $table) {
            $table->id();

            $table->date('fecha_encuesta')->nullable();
            $table->string('lugar_aplicacion')->nullable();
            $table->string('corregimiento')->nullable();

            $table->foreignId('corregimiento_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vereda_id')->constrained()->cascadeOnDelete();

            $table->string('finca')->nullable();

            $table->decimal('area_predio', 10, 2)->nullable();
            $table->string('unidad_medida')->nullable();
            $table->string('coordenadas')->nullable();
            $table->decimal('area_total_disponible', 10, 2)->nullable();
            $table->string('unidad_medida2')->nullable();

            $table->decimal('altitud', 10, 2)->nullable();

            $table->string('nombre_identidad')->nullable();
            $table->string('primer_apellido')->nullable();
            $table->string('segundo_apellido')->nullable();
            $table->string('numero_documento')->nullable();
            $table->string('tipo_documento')->nullable();

            $table->date('fecha_expedicion')->nullable();
            $table->string('municipio_expedicion')->nullable();
            $table->string('departamento_expedicion')->nullable();

            $table->date('fecha_nacimiento')->nullable();
            $table->string('municipio_nacimiento')->nullable();
            $table->string('departamento_nacimiento')->nullable();

            $table->string('genero')->nullable();
            $table->string('correo')->nullable();
            $table->string('celular_1')->nullable();
            $table->string('celular_2')->nullable();

            $table->string('nivel_educativo')->nullable();
            $table->string('que_estudio')->nullable();

            $table->integer('actividades_agricolas')->nullable();
            $table->integer('actividades_pecuarias')->nullable();

            $table->integer('renta_ciudadana')->nullable();
            $table->integer('renta_joven')->nullable();
            $table->integer('colombia_mayor')->nullable();
            $table->integer('devolucion_iva')->nullable();
            $table->integer('pension')->nullable();
            $table->integer('arriendos')->nullable();
            $table->integer('empleo_formal')->nullable();
            $table->integer('actividad_comercial')->nullable();
            $table->integer('independiente')->nullable();
            $table->integer('otros')->nullable();

            $table->string('tiempo_viviendo_finca')->nullable();
            $table->string('medio_transporte_propio')->nullable();
            $table->string('tenencia_tierra')->nullable();
            $table->string('pertenencia_poblacion_especial')->nullable();
            $table->boolean('le_gustaria_estudiar')->nullable();
            $table->string('que_le_gustaria_estudiar')->nullable();
            $table->boolean('trabaja_actualmente')->nullable();
            $table->string('tipo_empleo')->nullable();
            $table->string('tipo_contrato')->nullable();

            $table->timestamps();
        });
    }
};
