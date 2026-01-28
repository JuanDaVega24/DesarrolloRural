<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GestionAgropecuaria extends Model
{
    use HasFactory;

    protected $table = 'gestion_agropecuaria';

    protected $fillable = [
        'encuesta_id',
        'participa',
        'año',
        'entidad_gestiono',
        'entidad_otro',
        'consistio',
        'credito',
        'aprobado',
        'fuentes',
        'destino_recursos',
        'tiene_creditos',
        'entidad',
        'valor_credito',
        'plazo',
        'fecha_aprobacion',
        'al_dia',
        'seguro',
        'personas',
        'cuantos',
        'jornales',
        'trabajo_colectivo',
        'valor_jornal',
    ];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }
}
