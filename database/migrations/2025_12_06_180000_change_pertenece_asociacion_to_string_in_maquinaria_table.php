<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maquinaria', function (Blueprint $table) {
            // Cambiar pertenece_asociacion de boolean a string
            $table->string('pertenece_asociacion')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('maquinaria', function (Blueprint $table) {
            // Revertir a boolean
            $table->boolean('pertenece_asociacion')->nullable()->change();
        });
    }
};
