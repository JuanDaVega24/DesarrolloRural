<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familiar extends Model
{
    use HasFactory;

    protected $table = 'familiares';

    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
        'encuesta_id',
        'nombre_completo',
        'fecha_nacimiento',
        'tipo_documento',
        'documento',
        'fecha_expedicion',
        'lugar_expedicion',
        'parentesco',
        'genero',
        'poblacion',
        'condicion',
        'sabe_leer',
        'estudia',
        'nivel_educativo',
        'celular',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_expedicion' => 'date',
        'sabe_leer' => 'boolean',
        'estudia' => 'boolean',
    ];

    /**
     * Relación: Cada familiar pertenece a una encuesta
     */
    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }
}
