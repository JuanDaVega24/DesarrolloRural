<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Usar SQL crudo para PostgreSQL - eliminar restricciones enum y cambiar tipos
        DB::statement('ALTER TABLE producciones DROP CONSTRAINT IF EXISTS producciones_unidad_area_cultivo_check');

        DB::statement('ALTER TABLE producciones ALTER COLUMN tipo_cultivo TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN area_cultivo TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN unidad_area_cultivo TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN cantidad_arboles_plantas TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN nivel_produccion TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN edades_cultivo TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN seguridad_alimentaria TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN uso_comercial TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN bajo_cubierta TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN cielo_abierto TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN hidroponia TYPE TEXT');

        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_nombre TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_alimentario TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_no_alimentario TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_presentacion TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_precio TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_capacidad TYPE TEXT');
        DB::statement('ALTER TABLE producciones ADD COLUMN producto_unidad_capacidad TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_tiene_etiqueta TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_tiene_registro TYPE TEXT');

        // Convertir campos restantes a TEXT para arrays
        DB::statement('ALTER TABLE producciones ALTER COLUMN forestal_modalidad TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN forestal_cantidad TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN vivero_especies TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN vivero_cantidad TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN pastos_especies TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN pastos_hectareas TYPE TEXT');
        DB::statement('ALTER TABLE producciones ALTER COLUMN pastos_productos TYPE TEXT');
    }

    public function down(): void
    {
        // Revertir a tipos originales - recrear enum primero
        DB::statement("CREATE TYPE unidad_area_enum AS ENUM('HA', 'MTS2')");

        DB::statement('ALTER TABLE producciones ALTER COLUMN tipo_cultivo TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE producciones ALTER COLUMN area_cultivo TYPE DECIMAL(10,2)');
        DB::statement('ALTER TABLE producciones ALTER COLUMN unidad_area_cultivo TYPE unidad_area_enum USING unidad_area_cultivo::unidad_area_enum');
        DB::statement('ALTER TABLE producciones ALTER COLUMN cantidad_arboles_plantas TYPE INTEGER USING cantidad_arboles_plantas::INTEGER');
        DB::statement('ALTER TABLE producciones ALTER COLUMN nivel_produccion TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE producciones ALTER COLUMN edades_cultivo TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE producciones ALTER COLUMN seguridad_alimentaria TYPE BOOLEAN USING seguridad_alimentaria::BOOLEAN');
        DB::statement('ALTER TABLE producciones ALTER COLUMN uso_comercial TYPE BOOLEAN USING uso_comercial::BOOLEAN');
        DB::statement('ALTER TABLE producciones ALTER COLUMN bajo_cubierta TYPE BOOLEAN USING bajo_cubierta::BOOLEAN');
        DB::statement('ALTER TABLE producciones ALTER COLUMN cielo_abierto TYPE BOOLEAN USING cielo_abierto::BOOLEAN');
        DB::statement('ALTER TABLE producciones ALTER COLUMN hidroponia TYPE BOOLEAN USING hidroponia::BOOLEAN');

        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_nombre TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_alimentario TYPE BOOLEAN USING producto_alimentario::BOOLEAN');
        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_no_alimentario TYPE BOOLEAN USING producto_no_alimentario::BOOLEAN');
        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_presentacion TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_precio TYPE DECIMAL(12,2)');
        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_capacidad TYPE DECIMAL(12,2)');
        DB::statement('ALTER TABLE producciones DROP COLUMN IF EXISTS producto_unidad_capacidad');
        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_tiene_etiqueta TYPE BOOLEAN USING producto_tiene_etiqueta::BOOLEAN');
        DB::statement('ALTER TABLE producciones ALTER COLUMN producto_tiene_registro TYPE BOOLEAN USING producto_tiene_registro::BOOLEAN');

        // Revertir campos restantes
        DB::statement('ALTER TABLE producciones ALTER COLUMN forestal_modalidad TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE producciones ALTER COLUMN forestal_cantidad TYPE INTEGER USING forestal_cantidad::INTEGER');
        DB::statement('ALTER TABLE producciones ALTER COLUMN vivero_especies TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE producciones ALTER COLUMN vivero_cantidad TYPE INTEGER USING vivero_cantidad::INTEGER');
        DB::statement('ALTER TABLE producciones ALTER COLUMN pastos_especies TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE producciones ALTER COLUMN pastos_hectareas TYPE DECIMAL(10,2)');
        DB::statement('ALTER TABLE producciones ALTER COLUMN pastos_productos TYPE VARCHAR(255)');
    }
};
