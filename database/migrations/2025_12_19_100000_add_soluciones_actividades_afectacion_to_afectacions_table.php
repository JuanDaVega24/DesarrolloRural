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
            $table->string('soluciones')->nullable();
            $table->string('actividades')->nullable();
            $table->string('afectacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('afectaciones', function (Blueprint $table) {
            $table->dropColumn(['soluciones', 'actividades', 'afectacion']);
        });
    }
};
