<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyectoProductivo extends Model
{
    use HasFactory;

    protected $table = 'proyectos_productivos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'sector',
        'costo_estimado',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'encuesta_id', // relación con encuestas si aplica
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'costo_estimado' => 'decimal:2',
    ];

    
}
