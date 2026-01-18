<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('usuario');

        // Filtros
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('tabla_afectada')) {
            $query->where('tabla_afectada', $request->tabla_afectada);
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('accion', 'like', "%{$search}%")
                    ->orWhere('tabla_afectada', 'like', "%{$search}%")
                    ->orWhere('registro_id', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Ordenar
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginación
        $logs = $query->paginate(20)->appends(request()->query());

        // Datos para filtros
        $acciones = AuditLog::select('accion')->distinct()->pluck('accion');
        $tablas = AuditLog::select('tabla_afectada')->distinct()->whereNotNull('tabla_afectada')->pluck('tabla_afectada');
        $usuarios = User::select('id', 'nombre')->get();

        return view('auditor.logs.index', compact('logs', 'acciones', 'tablas', 'usuarios'));
    }

    public function show($id)
    {
        $log = AuditLog::with('usuario')->findOrFail($id);
        return view('auditor.logs.show', compact('log'));
    }

    public function export(Request $request)
    {
        $query = AuditLog::with('usuario');

        // Aplicar los mismos filtros que en index
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('tabla_afectada')) {
            $query->where('tabla_afectada', $request->tabla_afectada);
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

        // Generar CSV
        $filename = 'auditoria_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Encabezados
            fputcsv($file, ['ID', 'Usuario', 'Acción', 'Tabla', 'Registro ID', 'IP', 'Fecha']);

            // Datos
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->usuario ? $log->usuario->name : 'N/A',
                    $log->accion,
                    $log->tabla_afectada ?? 'N/A',
                    $log->registro_id ?? 'N/A',
                    $log->ip_address ?? 'N/A',
                    $log->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
