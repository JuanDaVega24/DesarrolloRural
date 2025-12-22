<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Encuesta;  // <- ESTA LÍNEA ES NECESARIA

class Produccion extends Model
{
    use HasFactory;

    protected $table = 'producciones';

    protected $fillable = [

        // Relación con encuesta
        'encuesta_id',

        /* ==============================
         ACTIVIDADES AGRÍCOLAS
        ===============================*/
        'tipo_cultivo',
        'area_cultivo',            // numérico
        'unidad_area_cultivo',     // HA o MTS2
        'cantidad_arboles_plantas',
        'nivel_produccion',
        'edades_cultivo',
        'seguridad_alimentaria',   // booleano
        'uso_comercial',           // booleano
        'bajo_cubierta',           // booleano
        'cielo_abierto',           // booleano
        'hidroponia',              // booleano

        /* ==============================
         ACTIVIDADES AGROINDUSTRIALES
        ===============================*/
        'producto_nombre',
        'producto_alimentario',    // boolean
        'producto_no_alimentario', // boolean
        'producto_presentacion',
        'producto_precio',
        'producto_capacidad',
        'producto_unidad_capacidad',
        'producto_tiene_etiqueta', // boolean
        'producto_tiene_registro', // boolean

        /* ==============================
         ACTIVIDADES FORESTALES
        ===============================*/
        'forestal_modalidad',
        'forestal_cantidad',

        /* ==============================
         ACTIVIDAD VIVERO
        ===============================*/
        'vivero_especies',
        'vivero_cantidad',

        /* ==============================
         PASTOS NATURALES
        ===============================*/
        'pastos_especies',
        'pastos_hectareas',
        'pastos_productos',
    ];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }
}
