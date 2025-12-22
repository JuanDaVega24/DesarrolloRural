<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Corregimiento;

class CorregimientosSeeder extends Seeder
{
    public function run(): void
    {
        Corregimiento::firstOrCreate(
            ['id' => 1],
            ['nombre' => 'Corregimiento 1']
        );

        Corregimiento::firstOrCreate(
            ['id' => 2],
            ['nombre' => 'Corregimiento 2']
        );

        Corregimiento::firstOrCreate(
            ['id' => 3],
            ['nombre' => 'Corregimiento 3']
        );
    }
}
