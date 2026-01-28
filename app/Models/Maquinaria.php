<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maquinaria extends Model
{
    use HasFactory;

    protected $table = 'maquinaria';

    protected $fillable = [
        'encuesta_id',
        'maquinaria',
        'tiene_construccion',
        'pertenece_asociacion',
        'nombre_asociacion',
        'entidad_asesoria',
        'entidad_asesoria_nombre',
        'recibio_asesoria_ultimo_anio',

        // Maquinaria arrays (JSON)
        'tipo_maquinaria',
        'cantidad_maquinaria',
        'antiguedad_maquinaria',
        'estado_maquinaria',

        // Construcción arrays (JSON)
        'tipo_construccion',
        'antiguedad_construccion',
        'cantidad_construccion',
        'area_construccion',

        // Temas de asesoría
        'tema_buenas_practicas_agricolas',
        'pago_bpa',
        'tema_buenas_practicas_pecuarias',
        'pago_bpp',
        'tema_manejo_ambiental',
        'pago_ma',
        'tema_manejo_suelos',
        'pago_ms',
        'tema_manejo_postcosecha',
        'pago_mpc',
        'tema_comercializacion',
        'pago_comercializacion',
        'tema_asociatividad',
        'pago_asociatividad',
        'tema_credito',
        'pago_credito',
        'tema_empresarial',
        'pago_empresarial',
        'tema_tradicional',
        'pago_tradicional',
    ];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }
}
