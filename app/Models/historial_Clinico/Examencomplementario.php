<?php

namespace App\Models\historial_Clinico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HistoriaClinica;
use App\Models\User;

class Examencomplementario extends Model
{
    use HasFactory;

    protected $table = 'examenes_complementarios';

    protected $fillable = [
        'historia_id',
        'tipo_examen',
        'nombre_examen',
        'descripcion',
        'fecha_solicitud',
        'fecha_resultado',
        'resultados',
        'archivo_resultado',
        'estado',
        'profesional_solicita'
    ];

    protected $casts = [
        'fecha_solicitud' => 'date',
        'fecha_resultado' => 'date',
    ];

    // 🔗 RELACIONES

    public function historiaClinica()
    {
        return $this->belongsTo(HistoriaClinica::class, 'historia_id');
    }

    public function profesional()
    {
        return $this->belongsTo(User::class, 'profesional_solicita');
    }

    // 📊 MÉTODOS HELPER

    /**
     * Verificar si el examen está completado
     */
    public function estaCompletado(): bool
    {
        return $this->estado === 'completado';
    }

    /**
     * Verificar si el examen está pendiente
     */
    public function estaPendiente(): bool
    {
        return $this->estado === 'solicitado';
    }

    /**
     * Marcar como completado
     */
    public function marcarCompletado($resultados = null, $archivoRuta = null): void
    {
        $this->estado = 'completado';
        $this->fecha_resultado = now();
        if ($resultados) {
            $this->resultados = $resultados;
        }
        if ($archivoRuta) {
            $this->archivo_resultado = $archivoRuta;
        }
        $this->save();
    }

    /**
     * Obtener nombre legible del tipo de examen
     */
    public function getNombreTipoExamen(): string
    {
        return match($this->tipo_examen) {
            'radiografia_periapical' => 'Radiografía Periapical',
            'radiografia_panoramica' => 'Radiografía Panorámica',
            'radiografia_oclusal' => 'Radiografía Oclusal',
            'biometria_hematica' => 'Biometría Hemática',
            'glucosa' => 'Glucosa',
            'laboratorio_clinico' => 'Laboratorio Clínico',
            'biopsia' => 'Biopsia',
            'interconsulta' => 'Interconsulta',
            'otro' => 'Otro',
            default => 'Examen'
        };
    }

    /**
     * Obtener badge de estado
     */
    public function getBadgeEstado(): array
    {
        return match($this->estado) {
            'solicitado' => ['class' => 'warning', 'texto' => 'Solicitado'],
            'en_proceso' => ['class' => 'info', 'texto' => 'En Proceso'],
            'completado' => ['class' => 'success', 'texto' => 'Completado'],
            'cancelado' => ['class' => 'danger', 'texto' => 'Cancelado'],
            default => ['class' => 'secondary', 'texto' => 'Desconocido']
        };
    }

    /**
     * Verificar si tiene archivo adjunto
     */
    public function tieneArchivo(): bool
    {
        return !empty($this->archivo_resultado);
    }

    /**
     * Obtener días transcurridos desde la solicitud
     */
    public function getDiasTranscurridos(): int
    {
        return $this->fecha_solicitud->diffInDays(now());
    }

    // 🔍 SCOPES

    /**
     * Filtrar por historia clínica
     */
    public function scopeDeHistoria($query, $historiaId)
    {
        return $query->where('historia_id', $historiaId);
    }

    /**
     * Filtrar por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Filtrar exámenes pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'solicitado');
    }

    /**
     * Filtrar exámenes completados
     */
    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }

    /**
     * Filtrar por tipo de examen
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_examen', $tipo);
    }

    /**
     * Filtrar por profesional que solicitó
     */
    public function scopeSolicitadoPor($query, $profesionalId)
    {
        return $query->where('profesional_solicita', $profesionalId);
    }

    /**
     * Ordenar por fecha de solicitud más reciente
     */
    public function scopeRecientes($query)
    {
        return $query->orderBy('fecha_solicitud', 'desc');
    }
}
