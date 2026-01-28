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
        Schema::table('encuestadores', function (Blueprint $table) {
            $table->boolean('autorizacion_datos')->default(false)->after('observaciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encuestadores', function (Blueprint $table) {
            $table->dropColumn('autorizacion_datos');
        });
    }
};
