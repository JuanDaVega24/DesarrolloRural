<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Corregimiento extends Model
{
    protected $fillable = ['nombre'];

    public function veredas()
    {
        return $this->hasMany(Vereda::class);
    }
}
