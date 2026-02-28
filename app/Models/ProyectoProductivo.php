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
        'ano',
        'descripcion',
        'estado',
        'data',
        'origen', // 'manual' o 'excel'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function preguntas()
    {
        return $this->hasMany(FormularioPregunta::class, 'proyecto_id');
    }
}
