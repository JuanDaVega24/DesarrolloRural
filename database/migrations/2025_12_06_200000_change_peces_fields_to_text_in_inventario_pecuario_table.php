<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventario_pecuario', function (Blueprint $table) {
            // Cambiar campos de peces a TEXT para almacenar arrays JSON como strings
            $table->text('peces_especie')->nullable()->change();
            $table->text('peces_cosechas_anio')->nullable()->change();
            $table->text('peces_animales_cosecha')->nullable()->change();
            $table->text('peces_peso_promedio')->nullable()->change();
            $table->text('peces_produccion_total_anterior')->nullable()->change();
            $table->text('peces_comercializacion')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('inventario_pecuario', function (Blueprint $table) {
            // Revertir cambios (volver a tipos simples)
            $table->string('peces_especie')->nullable()->change();
            $table->integer('peces_cosechas_anio')->nullable()->change();
            $table->integer('peces_animales_cosecha')->nullable()->change();
            $table->decimal('peces_peso_promedio', 10, 2)->nullable()->change();
            $table->decimal('peces_produccion_total_anterior', 10, 2)->nullable()->change();
            $table->string('peces_comercializacion')->nullable()->change();
        });
    }
};
