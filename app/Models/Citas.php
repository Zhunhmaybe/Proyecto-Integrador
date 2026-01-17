<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citas extends Model
{
    use HasFactory;

    // 👇 Campos asignables (MUY IMPORTANTE para evitar errores de mass assignment)
    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'especialidad_id',
        'fecha_inicio',
        'estado',
        'motivo',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
    ];
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidades::class);
    }
}
