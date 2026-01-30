<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'auditoria';

    protected $fillable = [
        'usuario_id',
        'accion',
        'tabla_afectada',
        'registro_id',
        'valores_anteriores',
        'valores_nuevos',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'valores_anteriores' => 'array',
        'valores_nuevos' => 'array',
        'created_at' => 'datetime',
    ];

    // Cambiar a true ya que la tabla tiene created_at
    public $timestamps = true;
    
    // Solo usamos created_at, no updated_at
    const UPDATED_AT = null;

    /**
     * Relación con el usuario que realizó la acción
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Scope para filtrar por acción
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('accion', $action);
    }

    /**
     * Scope para filtrar por tabla
     */
    public function scopeByTable($query, $table)
    {
        return $query->where('tabla_afectada', $table);
    }

    /**
     * Scope para filtrar por usuario
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('usuario_id', $userId);
    }

    /**
     * Scope para filtrar por rango de fechas
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope para logs de hoy
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope para logs de esta semana
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope para logs de este mes
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }
}
