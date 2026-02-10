<?php

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

if (!function_exists('auditar')) {
    function auditar(
        string $accion,
        ?string $tabla = null,
        ?int $registroId = null,
        $antes = null,
        $despues = null
    ) {
        AuditLog::create([
            'usuario_id' => Auth::id(),
            'accion' => strtoupper($accion),
            'tabla_afectada' => $tabla,
            'registro_id' => $registroId,
            'valores_anteriores' => $antes,
            'valores_nuevos' => $despues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
