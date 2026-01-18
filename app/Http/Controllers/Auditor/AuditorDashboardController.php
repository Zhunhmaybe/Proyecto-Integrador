<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Citas;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditorDashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_logs' => AuditLog::count(),
            'logs_today' => AuditLog::whereDate('created_at', today())->count(),
            'logs_week' => AuditLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'total_users' => User::count(),
            'total_citas' => Citas::count(),
            'total_pacientes' => Paciente::count(),
        ];

        // Últimas acciones
        $recentLogs = AuditLog::with('usuario')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Acciones por tipo
        $actionsByType = AuditLog::select('accion', DB::raw('count(*) as total'))
            ->groupBy('accion')
            ->orderBy('total', 'desc')
            ->get();

        // Tablas más afectadas
        $tableActivity = AuditLog::select('tabla_afectada', DB::raw('count(*) as total'))
            ->whereNotNull('tabla_afectada')
            ->groupBy('tabla_afectada')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Usuarios más activos
        $activeUsers = AuditLog::select('usuario_id', DB::raw('count(*) as total'))
            ->whereNotNull('usuario_id')
            ->groupBy('usuario_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->with('usuario')
            ->get();

        return view('auditor.dashboard', compact(
            'stats',
            'recentLogs',
            'actionsByType',
            'tableActivity',
            'activeUsers'
        ));
    }
}
