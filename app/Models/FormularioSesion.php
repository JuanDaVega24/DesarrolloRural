<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FormularioSesion extends Model
{
    use HasFactory;

    protected $table = 'formulario_sesiones';

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'session_token',
        'datos_beneficiarios',
        'completada',
        'ultima_actividad',
    ];

    protected $casts = [
        'datos_beneficiarios' => 'array',
        'completada' => 'boolean',
        'ultima_actividad' => 'datetime',
    ];

    public function proyecto()
    {
        return $this->belongsTo(ProyectoProductivo::class, 'proyecto_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Generar un token único para la sesión
     */
    public static function generarToken()
    {
        return md5(uniqid(mt_rand(), true));
    }

    /**
     * Marcar la sesión como completada
     */
    public function marcarComoCompletada()
    {
        $this->update([
            'completada' => true,
            'ultima_actividad' => now()
        ]);
    }

    /**
     * Limpiar sesiones inactivas (más de 2 horas sin actividad y no completadas)
     */
    public static function limpiarSesionesInactivas()
    {
        self::where('completada', false)
            ->where('ultima_actividad', '<', now()->subHours(2))
            ->delete();
    }

    /**
     * Comprobar si la sesión está activa (última actividad hace menos de 30 minutos)
     */
    public function estaActiva()
    {
        return !$this->completada && $this->ultima_actividad->diffInMinutes(now()) < 30;
    }

    /**
     * Comprobar si hay sesiones activas para un proyecto
     */
    public static function tieneSesionesActivas($proyectoId)
    {
        return self::where('proyecto_id', $proyectoId)
            ->where('completada', false)
            ->where('ultima_actividad', '>', now()->subMinutes(30))
            ->exists();
    }

    /**
     * Obtiene o crea una sesión para un proyecto y usuario
     */
    public static function obtenerOcrearSesion($proyectoId, $userId)
    {
        // Buscar sesión activa
        $sesion = static::where('proyecto_id', $proyectoId)
                      ->where('user_id', $userId)
                      ->where('completada', false)
                      ->first();

        if (!$sesion) {
            // Crear nueva sesión
            $sesion = static::create([
                'proyecto_id' => $proyectoId,
                'user_id' => $userId,
                'session_token' => Str::random(32),
                'datos_beneficiarios' => [],
                'completada' => false,
                'ultima_actividad' => now()
            ]);
        } else {
            // Actualizar última actividad
            $sesion->update(['ultima_actividad' => now()]);
        }

        return $sesion;
    }
}
