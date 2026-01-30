<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Citas;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuditorDashboardController extends Controller
{
    /**
     * Muestra el dashboard principal del auditor con estadísticas completas
     */
    public function index()
    {
        // ===== ESTADÍSTICAS PRINCIPALES =====
        
        // Total de logs
        $totalLogs = AuditLog::count();

        // Logs de hoy
        $logsHoy = AuditLog::whereDate('created_at', Carbon::today())->count();

        // Total de usuarios
        $totalUsuarios = User::count();

        // Total de citas
        $totalCitas = Citas::count();

        // ===== ACCIONES POR TIPO =====
        // Obtiene el conteo de cada tipo de acción (INSERT, UPDATE, DELETE, LOGIN)
        $accionesPorTipo = AuditLog::select('accion', DB::raw('count(*) as total'))
            ->groupBy('accion')
            ->orderBy('total', 'desc')
            ->get();

        // ===== TABLAS MÁS AFECTADAS =====
        // Top 5 tablas con más modificaciones
        $tablasMasAfectadas = AuditLog::select('tabla_afectada', DB::raw('count(*) as total'))
            ->whereNotNull('tabla_afectada')
            ->groupBy('tabla_afectada')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // ===== ÚLTIMAS ACCIONES =====
        // Últimas 10 acciones registradas en el sistema
        $ultimasAcciones = AuditLog::with('usuario')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ===== USUARIOS MÁS ACTIVOS =====
        // Top 5 usuarios con más acciones registradas
        $usuariosMasActivos = AuditLog::select('usuario_id', DB::raw('count(*) as total_acciones'))
            ->whereNotNull('usuario_id')
            ->groupBy('usuario_id')
            ->orderBy('total_acciones', 'desc')
            ->limit(5)
            ->with('usuario')
            ->get();

        // ===== CÁLCULOS PARA GRÁFICOS =====
        // Máximo para las barras de progreso (evita división por cero)
        $maxAcciones = $accionesPorTipo->max('total') ?? 1;
        $maxTablas = $tablasMasAfectadas->max('total') ?? 1;

        // ===== ESTADÍSTICAS ADICIONALES =====
        $stats = [
            'total_logs' => $totalLogs,
            'logs_today' => $logsHoy,
            'logs_week' => AuditLog::whereBetween('created_at', [
                now()->startOfWeek(), 
                now()->endOfWeek()
            ])->count(),
            'logs_month' => AuditLog::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'total_users' => $totalUsuarios,
            'total_citas' => $totalCitas,
            'total_pacientes' => Paciente::count(),
        ];

        // ===== ESTADÍSTICAS DE SEGURIDAD =====
        // Conteo de intentos de login del día
        $loginsHoy = AuditLog::whereDate('created_at', Carbon::today())
            ->where('accion', 'LOGIN')
            ->count();

        // IPs únicas que han accedido hoy
        $ipsUnicasHoy = AuditLog::whereDate('created_at', Carbon::today())
            ->whereNotNull('ip_address')
            ->distinct('ip_address')
            ->count('ip_address');

        // ===== ACTIVIDAD POR HORA (últimas 24 horas) =====
        // Compatible con PostgreSQL usando EXTRACT
        try {
            $actividadPorHora = AuditLog::select(
                    DB::raw('EXTRACT(HOUR FROM created_at)::integer as hora'),
                    DB::raw('count(*) as total')
                )
                ->where('created_at', '>=', now()->subHours(24))
                ->groupBy(DB::raw('EXTRACT(HOUR FROM created_at)'))
                ->orderBy('hora')
                ->get();
        } catch (\Exception $e) {
            // Si hay error, crear colección vacía
            $actividadPorHora = collect();
        }

        return view('auditor.dashboard', compact(
            // Estadísticas principales
            'totalLogs',
            'logsHoy',
            'totalUsuarios',
            'totalCitas',
            
            // Datos para gráficos
            'accionesPorTipo',
            'tablasMasAfectadas',
            'ultimasAcciones',
            'usuariosMasActivos',
            
            // Máximos para barras de progreso
            'maxAcciones',
            'maxTablas',
            
            // Estadísticas adicionales
            'stats',
            'loginsHoy',
            'ipsUnicasHoy',
            'actividadPorHora'
        ));
    }

    /**
     * Muestra todos los logs con filtros y búsqueda
     */
    public function logs(Request $request)
    {
        $query = AuditLog::with('usuario');

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

        // Búsqueda por IP
        if ($request->filled('ip')) {
            $query->where('ip_address', 'like', '%' . $request->ip . '%');
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(50);

        // Datos para los filtros
        $acciones = AuditLog::distinct()->pluck('accion');
        $tablas = AuditLog::distinct()->whereNotNull('tabla_afectada')->pluck('tabla_afectada');
        $usuarios = User::select('id', 'nombre')->orderBy('nombre')->get();

        return view('auditor.logs.index', compact('logs', 'acciones', 'tablas', 'usuarios'));
    }

    /**
     * Muestra detalle de un log específico
     */
    public function logDetail($id)
    {
        $log = AuditLog::with('usuario')->findOrFail($id);
        
        return view('auditor.logs.detail', compact('log'));
    }

    /**
     * Muestra la tabla de citas con filtros
     */
    public function citas(Request $request)
    {
        $query = Citas::with(['paciente', 'doctor']);

        // Filtros básicos
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        $citas = $query->orderBy('created_at', 'desc')->paginate(50);

        return view('auditor.tables.citas', compact('citas'));
    }

    /**
     * Muestra la tabla de pacientes con búsqueda
     */
    public function pacientes(Request $request)
    {
        $query = Paciente::query();

        // Búsqueda por nombre o cédula
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                  ->orWhere('cedula', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $pacientes = $query->orderBy('created_at', 'desc')->paginate(50);

        return view('auditor.tables.pacientes', compact('pacientes'));
    }

    /**
     * Muestra la tabla de usuarios del sistema
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filtro por rol
        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        // Búsqueda por nombre o email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(50);

        // Roles disponibles para filtro
        $roles = User::distinct()->pluck('rol');

        return view('auditor.tables.users', compact('users', 'roles'));
    }

    /**
     * Exporta logs a CSV
     */
    public function exportLogs(Request $request)
    {
        $query = AuditLog::with('usuario');

        // Aplicar los mismos filtros que en logs()
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }
        if ($request->filled('tabla')) {
            $query->where('tabla_afectada', $request->tabla);
        }
        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'audit_logs_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Encabezados
            fputcsv($file, ['ID', 'Usuario', 'Acción', 'Tabla', 'Registro ID', 'IP', 'Fecha']);
            
            // Datos
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->usuario ? $log->usuario->nombre : 'Sistema',
                    $log->accion,
                    $log->tabla_afectada ?? 'N/A',
                    $log->registro_id ?? 'N/A',
                    $log->ip_address ?? 'N/A',
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Obtiene estadísticas en tiempo real para AJAX
     */
    public function getRealtimeStats()
    {
        return response()->json([
            'total_logs' => AuditLog::count(),
            'logs_today' => AuditLog::whereDate('created_at', Carbon::today())->count(),
            'logs_last_hour' => AuditLog::where('created_at', '>=', now()->subHour())->count(),
            'active_users' => User::where('last_login', '>=', now()->subMinutes(15))->count(),
        ]);
    }
}