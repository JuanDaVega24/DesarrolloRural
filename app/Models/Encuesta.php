<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    use HasFactory;

    protected $table = 'encuestas';

    /**
     * Casts
     */
    protected $casts = [
        'fecha_encuesta' => 'date',
        'fecha_expedicion' => 'date',
        'fecha_nacimiento' => 'date',
        'familiares' => 'array',
        'le_gustaria_estudiar' => 'boolean',
        'trabaja_actualmente' => 'boolean',
    ];

    /**
     * SOLO CAMPOS PRINCIPALES
     */
      protected $fillable = [
        'fecha_encuesta',
        'lugar_aplicacion',
        'corregimiento_id',
        'vereda_id',
        'finca',
        'area_predio',
        'unidad_medida',
        'coordenadas',
        'area_total_disponible',
        'unidad_medida2',
        'altitud',
        'nombre_identidad',
        'primer_apellido',
        'segundo_apellido',
        'numero_documento',
        'tipo_documento',
        'fecha_expedicion',
        'municipio_expedicion',
        'departamento_expedicion',
        'fecha_nacimiento',
        'municipio_nacimiento',
        'departamento_nacimiento',
        'genero',
        'correo',
        'celular_1',
        'celular_2',
        'nivel_educativo',
        'que_estudio',
        'actividades_agricolas',
        'actividades_pecuarias',
        'renta_ciudadana',
        'renta_joven',
        'colombia_mayor',
        'devolucion_iva',
        'pension',
        'arriendos',
        'empleo_formal',
        'actividad_comercial',
        'independiente',
        'otros',
        'tiempo_viviendo_finca',
        'medio_transporte_propio',
        'tenencia_tierra',
        'pertenencia_poblacion_especial',
        'le_gustaria_estudiar',
        'que_le_gustaria_estudiar',
        'trabaja_actualmente',
        'tipo_empleo',
        'tipo_contrato',
        'tipo_tenencia',
        'familiares',
    ];

    /**
     * ACCESORES
     */
    public function getEdadAttribute()
    {
        if ($this->fecha_nacimiento) {
            return $this->fecha_nacimiento->age;
        }
        return null;
    }

    /**
     * RELACIONES  (18 TABLAS HIJAS)
     */

   public function vivienda()
{
    return $this->hasOne(Vivienda::class);
}

public function descripcion()
{
    return $this->hasOne(Descripcion::class);
}

public function produccion()
{
    return $this->hasOne(Produccion::class);
}

public function inventario_pecuario()
{
    return $this->hasOne(InventarioPecuario::class);
}

public function maquinaria()
{
    return $this->hasOne(Maquinaria::class);
}


    public function corregimiento()
    {
        return $this->belongsTo(Corregimiento::class);
    }

    public function vereda()
    {
        return $this->belongsTo(Vereda::class);
    }

    public function gestion_agropecuaria()
    {
        return $this->hasOne(GestionAgropecuaria::class);
    }

    public function predio()
    {
        return $this->hasOne(Predio::class);
    }

    public function controlActividade()
    {
        return $this->hasOne(controlActividade::class);
    }

       public function familiares()
{
    return $this->hasMany(Familiar::class);
}

    /**
     * Relación con el encuestador
     */
    public function encuestador()
    {
        return $this->hasOne(Encuestador::class);
    }

    /**
     * Relación con afectaciones
     */
    public function afectaciones()
    {
        return $this->hasMany(Afectacion::class);
    }

}
