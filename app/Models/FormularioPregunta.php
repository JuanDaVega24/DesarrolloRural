<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioPregunta extends Model
{
    use HasFactory;

    protected $table = 'formulario_preguntas';

    protected $fillable = [
        'proyecto_id',
        'pregunta',
        'subtitulo',
        'tipo_campo',
        'opciones',
        'imagenes',
        'es_obligatorio',
        'orden',
    ];

    protected $casts = [
        'opciones' => 'array',
        'imagenes' => 'array',
        'es_obligatorio' => 'boolean',
        'orden' => 'integer',
    ];

    public function proyecto()
    {
        return $this->belongsTo(ProyectoProductivo::class, 'proyecto_id');
    }
}