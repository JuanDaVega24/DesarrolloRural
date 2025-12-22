<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vereda;
use Maatwebsite\Excel\Facades\Excel;

class VeredasSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data\NOMBRES DE LAS VEREDAS.xlsx');

        $rows = Excel::toArray([], $path)[0];

        foreach ($rows as $index => $row) {

            if ($index === 0) continue; // saltar encabezados

            $corregimientoId = $row[0];   // 1,2,3
            $veredaNombre = trim($row[1]);

            if (!$veredaNombre) continue;

            Vereda::firstOrCreate([
                'nombre' => $veredaNombre,
                'corregimiento_id' => $corregimientoId,
            ]);
        }
    }
}
