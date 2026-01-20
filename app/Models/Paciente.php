<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HistoriaClinica;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $fillable = [
        'cedula',
        'nombres',
        'apellidos',
        'email',
        'telefono',
        'fecha_nacimiento',
        'direccion',
        'consentimiento_lopdp',
        'fecha_firma_lopdp',
        'datos_dinamicos',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_firma_lopdp' => 'datetime',
        'consentimiento_lopdp' => 'boolean',
        'datos_dinamicos' => 'array',
    ];

    /* ======================
       RELACIONES
       ====================== */

    public function citas()
    {
        return $this->hasMany(Citas::class)->orderBy('fecha_inicio', 'desc');
    }

    public function historiasClinicas()
    {
        return $this->hasMany(HistoriaClinica::class, 'paciente_id');
    }
}
