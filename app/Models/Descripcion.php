<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descripcion extends Model
{
    use HasFactory;

    protected $table = 'descripcions';

    protected $fillable = [

        'acueducto_publico',
        'acuifero',
        'almacenamiento_aguas_lluvias',
        'aljibes',
        'carrotanque',
        'nacimientos',
        'pila_publica',
        'pozos',
        'red_distribucion_comunitaria',
        'acueducto_veredal',
        'rios',
        'quebradas',
        'otro',

        'herramienta_agricola',
        'distancia_finca_cabecera',
        'transporte_cabecera',
        'vias_acceso',
        'condicion_vias',

        // Uso del suelo (Hectáreas o m2)
        'uso_suelo_agricultura',
        'uso_suelo_ganaderia',
        'uso_suelo_conservacion',
        'uso_suelo_casa',
        'uso_suelo_rastrojo',

        // Almacenamientos
        'almacen_maquinaria',
        'almacen_insumos_quimicos',
        'almacen_abonos',

        // Terreno y producción
        'condicion_terreno',
        'sistema_riego',
        'destino_produccion',
        'encuesta_id',

    ];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }
}
