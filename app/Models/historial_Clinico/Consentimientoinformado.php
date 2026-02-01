<?php

namespace App\Models\historial_Clinico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Paciente;
use App\Models\HistoriaClinica;
use App\Models\Tratamiento;
use App\Models\User;

class Consentimientoinformado extends Model
{
    use HasFactory;

    protected $table = 'consentimientos_informados';

    protected $fillable = [
        'paciente_id',
        'historia_id',
        'tratamiento_id',
        'tipo_consentimiento',
        'propositos',
        'procedimientos_propuestos',
        'resultados_esperados',
        'riesgos_complicaciones',
        'intervenciones_quirurgicas',
        'riesgos_quirurgicos',
        'anestesia_propuesta',
        'riesgos_anestesicos',
        'nombre_anestesiologo',
        'informacion_satisfactoria',
        'actividades_explicadas',
        'beneficios_riesgos_comprendidos',
        'garantia_calidad_comprendida',
        'respeto_intimidad_garantizado',
        'derecho_anular_comprendido',
        'informacion_completa_entregada',
        'firma_paciente',
        'cedula_paciente',
        'fecha_firma_paciente',
        'nombre_representante',
        'cedula_representante',
        'parentesco_representante',
        'firma_representante',
        'fecha_firma_representante',
        'nombre_testigo',
        'cedula_testigo',
        'firma_testigo',
        'profesional_id',
        'firma_profesional',
        'fecha_firma_profesional',
        'documento_pdf',
        'estado'
    ];

    protected $casts = [
        'fecha_firma_paciente' => 'datetime',
        'fecha_firma_representante' => 'datetime',
        'fecha_firma_profesional' => 'datetime',
        'informacion_satisfactoria' => 'boolean',
        'actividades_explicadas' => 'boolean',
        'beneficios_riesgos_comprendidos' => 'boolean',
        'garantia_calidad_comprendida' => 'boolean',
        'respeto_intimidad_garantizado' => 'boolean',
        'derecho_anular_comprendido' => 'boolean',
        'informacion_completa_entregada' => 'boolean',
    ];


    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function historiaClinica()
    {
        return $this->belongsTo(HistoriaClinica::class, 'historia_id');
    }

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class, 'tratamiento_id');
    }

    public function profesional()
    {
        return $this->belongsTo(User::class, 'profesional_id');
    }

    /**
     * Verificar si el consentimiento está firmado
     */
    public function estaFirmado(): bool
    {
        return $this->estado === 'firmado' && 
               !empty($this->firma_paciente) && 
               !empty($this->fecha_firma_paciente);
    }

    /**
     * Verificar si está pendiente
     */
    public function estaPendiente(): bool
    {
        return $this->estado === 'pendiente';
    }

    /**
     * Marcar como firmado
     */
    public function marcarFirmado(): void
    {
        $this->estado = 'firmado';
        $this->fecha_firma_paciente = now();
        $this->save();
    }

    /**
     * Anular consentimiento
     */
    public function anular(): void
    {
        $this->estado = 'anulado';
        $this->save();
    }

    /**
     * Obtener nombre del tipo de consentimiento
     */
    public function getNombreTipo(): string
    {
        return match($this->tipo_consentimiento) {
            'tratamiento_general' => 'Tratamiento General',
            'intervencion_quirurgica' => 'Intervención Quirúrgica',
            'anestesia' => 'Anestesia',
            'cirugia' => 'Cirugía',
            'procedimiento_diagnostico' => 'Procedimiento Diagnóstico',
            'extraccion_organos' => 'Extracción de Órganos',
            'necropsia' => 'Necropsia',
            'retiro_menor' => 'Retiro de Menor',
            'exoneracion_aborto' => 'Exoneración por Aborto',
            'abandono_hospital' => 'Abandono de Hospital',
            default => 'Consentimiento'
        };
    }

    /**
     * Verificar si todos los checkboxes obligatorios están marcados
     */
    public function todosCheckboxMarcados(): bool
    {
        return $this->informacion_satisfactoria &&
               $this->actividades_explicadas &&
               $this->beneficios_riesgos_comprendidos &&
               $this->garantia_calidad_comprendida &&
               $this->respeto_intimidad_garantizado &&
               $this->derecho_anular_comprendido &&
               $this->informacion_completa_entregada;
    }

    /**
     * Verificar si requiere representante legal
     */
    public function requiereRepresentante(): bool
    {
        return in_array($this->tipo_consentimiento, [
            'retiro_menor',
            'extraccion_organos'
        ]);
    }

    /**
     * Obtener badge de estado
     */
    public function getBadgeEstado(): array
    {
        return match($this->estado) {
            'pendiente' => ['class' => 'warning', 'texto' => 'Pendiente'],
            'firmado' => ['class' => 'success', 'texto' => 'Firmado'],
            'rechazado' => ['class' => 'danger', 'texto' => 'Rechazado'],
            'anulado' => ['class' => 'secondary', 'texto' => 'Anulado'],
            default => ['class' => 'secondary', 'texto' => 'Desconocido']
        };
    }

    /**
     * Verificar si tiene documento PDF generado
     */
    public function tienePDF(): bool
    {
        return !empty($this->documento_pdf);
    }

    /**
     * Filtrar por paciente
     */
    public function scopeDePaciente($query, $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }

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
     * Filtrar consentimientos firmados
     */
    public function scopeFirmados($query)
    {
        return $query->where('estado', 'firmado');
    }

    /**
     * Filtrar consentimientos pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Filtrar por tipo de consentimiento
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_consentimiento', $tipo);
    }

    /**
     * Filtrar por profesional
     */
    public function scopePorProfesional($query, $profesionalId)
    {
        return $query->where('profesional_id', $profesionalId);
    }

    /**
     * Ordenar por fecha de creación más reciente
     */
    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
