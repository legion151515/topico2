<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atencion extends Model
{
    protected $fillable = [
        'paciente_id',
        'user_id',  // Usuario que atendió al paciente
        'categoria',
        'nivel_id',
        'semestre',
        'grado',
        'nivel_escuela',
        'anios',
        'otros_especificacion',
        'motivo_id',
        'motivo_otro',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'tipo_salida',
        'token_firma',
        'observaciones'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function motivo()
    {
        return $this->belongsTo(MotivoConsulta::class, 'motivo_id');
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    public function medicamentos()
    {
        return $this->belongsToMany(Medicamento::class, 'atencion_medicamento')->withPivot('cantidad_usada', 'observaciones');
    }
}