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
        Schema::table('proyectos_productivos', function (Blueprint $table) {
            $table->enum('origen', ['manual', 'excel'])->default('excel')->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyectos_productivos', function (Blueprint $table) {
            $table->dropColumn('origen');
        });
    }
};
