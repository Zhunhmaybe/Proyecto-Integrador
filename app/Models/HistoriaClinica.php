<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriaClinica extends Model
{
    use HasFactory;

    protected $table = 'historias_clinicas';

    protected $fillable = [
        'paciente_id',
        'numero_historia',
        'fecha_atencion',
        'estado_historia',
        'motivo_consulta',
        'enfermedad_actual',

        // Antecedentes personales
        'alergias',
        'cardiopatias',
        'diabetes',
        'hipertension',
        'tuberculosis',
        'antecedentes_otros',

        // Antecedentes familiares
        'fam_diabetes',
        'fam_hipertension',
        'fam_cancer',
        'fam_tuberculosis',

        // Constantes vitales
        'temperatura',
        'presion_arterial',
        'pulso',
        'frecuencia_respiratoria',

        // Examen clínico
        'labios',
        'lengua',
        'paladar',
        'piso_boca',
        'encias',
        'carrillos',
        'orofaringe',
        'atm',

        // Observaciones
        'observaciones',

        // Auditoría
        'profesional_id'
    ];

    protected $casts = [
        'fecha_atencion' => 'date',
        'cardiopatias' => 'boolean',
        'diabetes' => 'boolean',
        'hipertension' => 'boolean',
        'tuberculosis' => 'boolean',
        'fam_diabetes' => 'boolean',
        'fam_hipertension' => 'boolean',
        'fam_cancer' => 'boolean',
        'fam_tuberculosis' => 'boolean',
    ];

    // 🔗 Relaciones
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class, 'historia_id');
    }

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'historia_id');
    }
}
