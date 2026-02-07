<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Citas;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableViewController extends Controller
{
    public function users(Request $request)
{
    $query = User::query();

    // 🔎 Buscar
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nombre', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // 🎯 Filtro por rol (IMPORTANTE: usar filled + !== null)
    if ($request->has('rol') && $request->rol !== '') {
        $query->where('rol', (int)$request->rol);
    }

    $users = $query->orderBy('created_at', 'desc')
                   ->paginate(20)
                   ->appends(request()->query());

    // 📊 Estadísticas (según tus roles reales)
    $stats = [
        'total' => User::count(),
        'doctor' => User::where('rol', 0)->count(),
        'admin' => User::where('rol', 1)->count(),
        'auditor' => User::where('rol', 2)->count(),
        'recepcion' => User::where('rol', 3)->count(),
        'usuario' => User::where('rol', 4)->count(),
    ];

    return view('auditor.tables.users', compact('users', 'stats'));
}

    //Citas
    public function citas(Request $request)
    {
        $query = Citas::with(['paciente', 'especialidad']);

        if ($request->filled('search')) {
            $query->where('motivo', 'like', "%{$request->search}%")
                ->orWhere('estado', 'like', "%{$request->search}%");
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_inicio', '>=', $request->fecha_inicio);
        }

        $citas = $query->orderBy('fecha_inicio', 'desc')
            ->paginate(20)
            ->appends(request()->query());

        // ✅ ESTADÍSTICAS (SIMPLES)
        $totalCitas = Citas::count();
        $citasPendientes = Citas::where('estado', 'pendiente')->count();
        $citasConfirmadas = Citas::where('estado', 'confirmada')->count();
        $citasCanceladas = Citas::where('estado', 'cancelada')->count();

        $estados = Citas::select('estado')->distinct()->pluck('estado');

        return view('auditor.tables.citas', compact(
            'citas',
            'estados',
            'totalCitas',
            'citasPendientes',
            'citasConfirmadas',
            'citasCanceladas'
        ));
    }

    public function pacientes(Request $request)
    {
        $query = Paciente::query();

        // 🔎 Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                    ->orWhere('apellidos', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telefono', 'like', "%{$search}%");
            });
        }

        $pacientes = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends(request()->query());

        // ✅ ESTADÍSTICAS
        $totalPacientes = Paciente::count();
        $pacientesHoy = Paciente::whereDate('created_at', now())->count();
        $pacientesMes = Paciente::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('auditor.tables.pacientes', compact(
            'pacientes',
            'totalPacientes',
            'pacientesHoy',
            'pacientesMes'
        ));
    }


    public function customQuery(Request $request)
    {
        // Vista para ejecutar consultas SQL personalizadas (solo lectura)
        $results = null;
        $error = null;

        if ($request->filled('query')) {
            try {
                // Validar que sea solo SELECT
                $sqlQuery = trim($request->input('query'));
                if (!preg_match('/^SELECT/i', $sqlQuery)) {
                    throw new \Exception('Solo se permiten consultas SELECT');
                }

                $results = DB::select($sqlQuery);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return view('auditor.tables.custom_query', compact('results', 'error'));
    }
}
