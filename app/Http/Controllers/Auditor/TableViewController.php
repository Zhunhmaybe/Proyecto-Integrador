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

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('rol', 'like', "%{$search}%");
            });
        }

        // Filtro por rol
        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20)->appends(request()->query());
        $roles = User::select('rol')->distinct()->pluck('rol');

        return view('auditor.tables.users', compact('users', 'roles'));
    }

    public function citas(Request $request)
    {
        $query = Citas::with(['paciente', 'especialidad']);

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('motivo', 'like', "%{$search}%")
                    ->orWhere('estado', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por fecha
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_inicio', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_inicio', '<=', $request->fecha_fin);
        }

        $citas = $query->orderBy('fecha_inicio', 'desc')->paginate(20)->appends(request()->query());
        $estados = Citas::select('estado')->distinct()->pluck('estado');

        return view('auditor.tables.citas', compact('citas', 'estados'));
    }

    public function pacientes(Request $request)
    {
        $query = Paciente::query();

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('apellido', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telefono', 'like', "%{$search}%");
            });
        }

        $pacientes = $query->orderBy('created_at', 'desc')->paginate(20)->appends(request()->query());

        return view('auditor.tables.pacientes', compact('pacientes'));
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
