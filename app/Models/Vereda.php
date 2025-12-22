<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vereda extends Model
{
    protected $fillable = ['corregimiento_id', 'nombre'];

    public function corregimiento()
    {
        return $this->belongsTo(Corregimiento::class);
    }
}
