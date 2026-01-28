<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encuestador extends Model
{
    use HasFactory;

    protected $table = 'encuestadores';

    protected $fillable = [
        'encuesta_id',
        'user_id',
        'nombre_encuestador',
        'documento_encuestador',
        'telefono_encuestador',
        'observaciones',
        'autorizacion_datos',
    ];

    /**
     * Relación con la encuesta
     */
    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
