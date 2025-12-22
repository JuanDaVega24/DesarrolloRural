<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maquinaria', function (Blueprint $table) {
            // Arrays JSON para maquinaria
            $table->json('tipo_maquinaria')->nullable();
            $table->json('cantidad_maquinaria')->nullable();
            $table->json('antiguedad_maquinaria')->nullable();
            $table->json('estado_maquinaria')->nullable();

            // Arrays JSON para construcción
            $table->json('tipo_construccion')->nullable();
            $table->json('antiguedad_construccion')->nullable();
            $table->json('cantidad_construccion')->nullable();
            $table->json('area_construccion')->nullable();

            // Campos adicionales de asesoría que faltaban
            $table->boolean('tema_comercializacion')->nullable();
            $table->boolean('pago_comercializacion')->nullable();
            $table->boolean('tema_asociatividad')->nullable();
            $table->boolean('pago_asociatividad')->nullable();
            $table->boolean('tema_credito')->nullable();
            $table->boolean('pago_credito')->nullable();
            $table->boolean('tema_empresarial')->nullable();
            $table->boolean('pago_empresarial')->nullable();
            $table->boolean('tema_tradicional')->nullable();
            $table->boolean('pago_tradicional')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('maquinaria', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_maquinaria',
                'cantidad_maquinaria',
                'antiguedad_maquinaria',
                'estado_maquinaria',
                'tipo_construccion',
                'antiguedad_construccion',
                'cantidad_construccion',
                'area_construccion',
                'tema_comercializacion',
                'pago_comercializacion',
                'tema_asociatividad',
                'pago_asociatividad',
                'tema_credito',
                'pago_credito',
                'tema_empresarial',
                'pago_empresarial',
                'tema_tradicional',
                'pago_tradicional'
            ]);
        });
    }
};
