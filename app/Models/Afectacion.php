<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Afectacion extends Model
{
    use HasFactory;

    protected $table = 'afectaciones';

    protected $fillable = [
        'encuesta_id',
        'actividad_productiva',
        'fenomeno',
        'anio',
        'semestre',
        'hectareas',
        'unidades_afectadas',
        'soluciones',
        'actividades',
        'afectacion',
        // si vas a tener múltiples columnas de afectaciones por actividad,
        // puedes usar un campo JSON 'detalles' para mayor flexibilidad
        'detalles',
    ];

    protected $casts = [
        'anio' => 'integer',
        'actividad_productiva' => 'array', // Cast to array para manejar JSON de checkboxes
        'soluciones' => 'array',
        'actividades' => 'array',
        'afectacion' => 'array',
        'hectareas' => 'array',
        'unidades_afectadas' => 'array',
        'detalles' => 'array',
    ];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }
}
