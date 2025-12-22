<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vivienda extends Model
{
    use HasFactory;

    protected $table = 'viviendas';

    protected $fillable = [
        'tipo_vivienda',
        'condicion_ocupacion',
        'material_piso',
        'material_pared_exterior',
        'destino_aguas_residuales',
        'combustible_cocina',
        'medios_comunicacion',
        'medios_electronicos',
        'acueducto_veredal',
        'cuenta_con_filtro',
        'tipo_servicio_sanitario',
        'encuesta_id', // si se relaciona con la encuesta
    ];

    public function encuesta()
{
    return $this->belongsTo(\App\Models\Encuesta::class);
}

}
