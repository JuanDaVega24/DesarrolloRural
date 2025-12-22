<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioPecuario extends Model
{
    use HasFactory;

    protected $table = 'inventario_pecuario';

    protected $fillable = [
        'encuesta_id',

        // Ganado bovino
        'tiene_ganado_bovino',
        'orientacion_ganadera',
        'manejo_alimentacion',
        'vacunas_recibidas',
        'pago_biologico',

        'bovino_machos_menor1',
        'bovino_machos_1a3',
        'bovino_machos_mayor3',
        'bovino_machos_reproductores',

        'bovino_hembras_menor1',
        'bovino_hembras_1a3',
        'bovino_hembras_mayor3',
        'bovino_hembras_ordeño',

        'produccion_leche_litros',
        'destino_leche',
        'comercializacion_leche',

        // Cerdos
        'tiene_cerdos',
        'orientacion_porcicola',
        'vacuna_peste_clasica',
        'cerdos_machos_reproductores',
        'cerdos_hembras_gestantes',
        'cerdos_hembras_reemplazo',
        'cerdos_descartes',
        'cerdos_destetos_anio',
        'cerdos_ceba_anio',

        // Aves
        'cria_gallinas_pollos_galpon',
        'aves_ponedoras',
        'aves_pollos_engorde',
        'aves_genetica_huevo',
        'aves_genetica_engorde',
        'produccion_huevos_mes',
        'comercializacion_huevos',
        'pollo_comercializado_kg_mes',
        'donde_comercializo_pollo',
        'metodo_sacrificio',
        'orientacion_avicola',
        'aves_ornamentales',

        // Peces
        'cria_peces',
        'peces_especie',
        'peces_cosechas_anio',
        'peces_animales_cosecha',
        'peces_peso_promedio',
        'peces_produccion_total_anterior',
        'peces_comercializacion',
        'peces_orientacion',

        // Otros animales
        'tiene_otros_animales',
        'caballos',
        'yeguas',
        'mulos',
        'mulas',
        'burros',
        'burras',
        'cabros',
        'cabras',
        'ovejos',
        'ovejas',
        'bufalos_machos',
        'bufalos_hembras',
        'vacuna_encefalitis_equina',
        'orientacion_ovino_caprina',

        // Traspatio
        'cerdos_traspatio',
        'gallos_pollos_traspatio',
        'gallos_pelea',
        'pavos',
        'patos_gansos',
        'codornices',
        'avestruces',
        'cuyes',
        'conejos',

        // Abejas
        'colmenas_miel',
        'colmenas_polen',
        'colmenas_subproductos',
        'colmenas_meliponas',

        // Mascotas
        'caninos_hembras',
        'caninos_machos',
        'felinos_hembras',
        'felinos_machos',
        'tortugas',

        // Otros
        'otros2',
        'esterilizados'
    ];

    /**
     * Relación con Encuesta
     */
    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }
}
