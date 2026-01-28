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
        Schema::table('afectaciones', function (Blueprint $table) {
            $table->json('soluciones')->nullable();
            $table->json('actividades')->nullable();
            $table->json('afectacion')->nullable();
            $table->json('hectareas')->nullable();
            $table->json('unidades_afectadas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('afectaciones', function (Blueprint $table) {
            $table->dropColumn(['soluciones', 'actividades', 'afectacion', 'hectareas', 'unidades_afectadas']);
        });
    }
};
