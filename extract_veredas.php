<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToArray;

class VeredasImport implements ToArray {
    public function array(array $array) {
        return $array;
    }
}

try {
    $data = Excel::toArray(new VeredasImport, 'database/data/NOMBRES DE LAS VEREDAS.xlsx');
    $sheet = $data[0];
    
    // Skip header
    array_shift($sheet);
    
    $veredasByCorregimiento = [];
    
    foreach ($sheet as $row) {
        if (empty($row[0]) || empty($row[1])) continue;
        
        $id = (string)$row[0];
        $vereda = trim($row[1]);
        
        if (!isset($veredasByCorregimiento[$id])) {
            $veredasByCorregimiento[$id] = [];
        }
        
        $veredasByCorregimiento[$id][] = $vereda;
    }
    
    // Sort keys just in case
    ksort($veredasByCorregimiento);
    
    $json = json_encode($veredasByCorregimiento, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    file_put_contents('resources/js/veredas.json', $json);
    echo "File resources/js/veredas.json created successfully.";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
