<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditorController extends Controller
{
    /**
     * Dashboard principal del auditor
     */
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_logs' => AuditLog::count(),
            'logs_today' => AuditLog::today()->count(),
            'total_users' => User::count(),
            'active_users' => User::where('estado', 1)->count(),
        ];

        // Logs recientes
        $recentLogs = AuditLog::with('usuario')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Actividad por acción
        $activityByAction = AuditLog::select('accion', DB::raw('count(*) as total'))
            ->groupBy('accion')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->accion => $item->total];
            });

        // Actividad por tabla
        $activityByTable = AuditLog::select('tabla_afectada', DB::raw('count(*) as total'))
            ->whereNotNull('tabla_afectada')
            ->groupBy('tabla_afectada')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return view('auditor.dashboard', compact('stats', 'recentLogs', 'activityByAction', 'activityByTable'));
    }

    /**
     * Mostrar logs de auditoría con filtros
     */
    public function logs(Request $request)
    {
        $query = AuditLog::with('usuario');

        // Filtro de búsqueda general
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('tabla_afectada', 'like', "%{$search}%")
                    ->orWhere('registro_id', 'like', "%{$search}%");
            });
        }

        // Filtro por acción
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        // Filtro por tabla
        if ($request->filled('tabla')) {
            $query->where('tabla_afectada', $request->tabla);
        }

        // Filtro por usuario
        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }

        // Filtro por rango de fechas
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        // Paginación
        $logs = $query->orderBy('created_at', 'desc')->paginate(15);

        // Datos para los filtros
        $usuarios = User::select('id', 'nombre')->get();
        $tablas = AuditLog::select('tabla_afectada')
            ->distinct()
            ->whereNotNull('tabla_afectada')
            ->pluck('tabla_afectada');

        return view('auditor.logs.index', compact('logs', 'usuarios', 'tablas'));
    }

    /**
     * Obtener detalle de un log específico
     */
    public function logDetail($id)
    {
        $log = AuditLog::with('usuario')->findOrFail($id);
        
        return response()->json([
            'id' => $log->id,
            'usuario' => $log->usuario ? [
                'id' => $log->usuario->id,
                'nombre' => $log->usuario->nombre
            ] : null,
            'accion' => $log->accion,
            'tabla_afectada' => $log->tabla_afectada,
            'registro_id' => $log->registro_id,
            'ip_address' => $log->ip_address,
            'user_agent' => $log->user_agent,
            'created_at' => $log->created_at->format('Y-m-d\TH:i:s'),
            'valores_anteriores' => $log->valores_anteriores,
            'valores_nuevos' => $log->valores_nuevos,
        ]);
    }

    /**
     * Exportar logs a CSV
     */
    public function exportLogs(Request $request)
    {
        $query = AuditLog::with('usuario');

        // Aplicar los mismos filtros que en la vista
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('tabla_afectada', 'like', "%{$search}%");
            });
        }

        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('tabla')) {
            $query->where('tabla_afectada', $request->tabla);
        }

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'logs_auditoria_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados
            fputcsv($file, [
                'ID',
                'Usuario',
                'Acción',
                'Tabla',
                'Registro ID',
                'IP',
                'User Agent',
                'Fecha'
            ]);

            // Datos
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->usuario ? $log->usuario->nombre : 'Sistema',
                    $log->accion,
                    $log->tabla_afectada ?? 'N/A',
                    $log->registro_id ?? 'N/A',
                    $log->ip_address ?? 'N/A',
                    $log->user_agent ?? 'N/A',
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Mostrar tabla de citas
     */
    public function citas()
    {
        $citas = Cita::with(['paciente', 'medico'])
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        return view('auditor.tables.citas', compact('citas'));
    }

    /**
     * Mostrar tabla de pacientes
     */
    public function pacientes()
    {
        $pacientes = Paciente::orderBy('created_at', 'desc')
            ->paginate(15);

        return view('auditor.tables.pacientes', compact('pacientes'));
    }

    /**
     * Mostrar tabla de usuarios
     */
    public function users()
    {
        $users = User::with('rol')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('auditor.tables.users', compact('users'));
    }

    /**
     * Obtener estadísticas en tiempo real (para AJAX)
     */
    public function getRealtimeStats()
    {
        return response()->json([
            'total_logs' => AuditLog::count(),
            'logs_today' => AuditLog::today()->count(),
            'logs_this_week' => AuditLog::thisWeek()->count(),
            'logs_this_month' => AuditLog::thisMonth()->count(),
        ]);
    }
}