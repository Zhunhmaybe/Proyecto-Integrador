<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Registra una acción en el log de auditoría
     */
    public static function log(
        string $accion,
        ?string $tablaAfectada = null,
        ?string $registroId = null,
        ?array $valoresAnteriores = null,
        ?array $valoresNuevos = null
    ) {
        try {
            AuditLog::create([
                'usuario_id' => Auth::id(),
                'accion' => $accion,
                'tabla_afectada' => $tablaAfectada,
                'registro_id' => $registroId,
                'valores_anteriores' => $valoresAnteriores,
                'valores_nuevos' => $valoresNuevos,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the application
            \Log::error('Error al registrar auditoría: ' . $e->getMessage());
        }
    }

    /**
     * Registra una creación
     */
    public static function logCreate(string $tabla, string $registroId, array $valores)
    {
        self::log('CREATE', $tabla, $registroId, null, $valores);
    }

    /**
     * Registra una actualización
     */
    public static function logUpdate(string $tabla, string $registroId, array $valoresAnteriores, array $valoresNuevos)
    {
        self::log('UPDATE', $tabla, $registroId, $valoresAnteriores, $valoresNuevos);
    }

    /**
     * Registra una eliminación
     */
    public static function logDelete(string $tabla, string $registroId, array $valores)
    {
        self::log('DELETE', $tabla, $registroId, $valores, null);
    }

    /**
     * Registra un login
     */
    public static function logLogin()
    {
        self::log('LOGIN');
    }

    /**
     * Registra un logout
     */
    public static function logLogout()
    {
        self::log('LOGOUT');
    }

    /**
     * Registra un acceso denegado
     */
    public static function logAccessDenied(string $recurso)
    {
        self::log('ACCESS_DENIED', null, null, ['recurso' => $recurso], null);
    }
}
