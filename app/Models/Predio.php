<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Predio extends Model
{
    use HasFactory;

    protected $table = 'predio';

    protected $fillable = [
        'encuesta_id',

        // Campos numéricos para uso del suelo
        'uso_agropecuario',
        'barbecho',
        'descanso',
        'rastrojos',
        'bosques_naturales',
        'construcciones_infraestructura_agropecuaria',
        'construcciones_infraestructura_no_agropecuaria',
        'otros_usos',

        // Campos de ubicación y predio (como JSON)
        'predio_no_continuo',
        'nombre_predio', // JSON
        'area', // JSON
        'area2', // JSON
        'vereda', // JSON
        'corregimiento', // JSON
        'municipio', // JSON
        'departamento', // JSON
        'tipo_actividad', // JSON
        'cantidad', // JSON
        'actividades_no_agropecuarias',
        'actividades',
    ];

    protected $casts = [
        // Campos que se almacenan como JSON en text
        'nombre_predio' => 'array',
        'area' => 'array',
        'area2' => 'array',
        'vereda' => 'array',
        'corregimiento' => 'array',
        'municipio' => 'array',
        'departamento' => 'array',
        'tipo_actividad' => 'array',
        'cantidad' => 'array',
    ];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }
}
