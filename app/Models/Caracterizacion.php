<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caracterizacion extends Model
{
    use HasFactory;

    protected $table = 'caracterizaciones';

    protected $fillable = [
        'nombre',
        'ano',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
