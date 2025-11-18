<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'estudiante_id',
        'fecha',
        'hora',
        'motivo',
        'estado',
        'observaciones_estudiante',
        'observaciones_personal',
        'atendido_por',
        'fecha_aprobacion',
        'fecha_rechazo',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_aprobacion' => 'datetime',
        'fecha_rechazo' => 'datetime',
    ];

    /**
     * Relación con el estudiante que solicita la cita
     */
    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    /**
     * Relación con el personal que atendió la cita
     */
    public function personalAtendio()
    {
        return $this->belongsTo(User::class, 'atendido_por');
    }

    /**
     * Scope para citas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para citas aprobadas
     */
    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobada');
    }

    /**
     * Scope para citas de hoy
     */
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', today());
    }

    /**
     * Scope para citas futuras
     */
    public function scopeFuturas($query)
    {
        return $query->where('fecha', '>=', today());
    }
}
