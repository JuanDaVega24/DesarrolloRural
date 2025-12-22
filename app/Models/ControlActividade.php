<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlActividade extends Model
{
    use HasFactory;

    protected $table = 'control_actividades';

    protected $fillable = [
        'encuesta_id',
        'unidad_productiva',
        'cuales',
        'fertilizantes',
        'tipo_fertilizantes',
        'frecuencia_aplicacion',
        'mecanismos',
        'analisis',
        'analisis_ayuda',
        'fecha_analisis',
        'control',
        'frecuencia',
        'control_plagas',
        'tipo_control',
        'conoce_BPA',
        'conoce_inocuidad',
        'desinfectar',
        'toxicidad',
        'proteccion',
        'cuales_proteccion',
        'plaguicidas',
        'tiempo_plaguicida',
        'cultivo_plaguicida',
        'envases_plaguicida',
        'calidad_predio',
        'analisis_agua',
        'cual_analisis',
    ];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }
}
