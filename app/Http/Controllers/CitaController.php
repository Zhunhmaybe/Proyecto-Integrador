<?php

namespace App\Http\Controllers;

use App\Models\Citas;
use App\Models\Paciente;
use App\Models\User;
use App\Models\Especialidades;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    // Vista principal y búsqueda de paciente
    public function create(Request $request)
    {
        $paciente = null;

        // Si hay una cédula en la petición (búsqueda GET)
        if ($request->has('cedula') && $request->cedula != null) {
            $paciente = Paciente::where('cedula', $request->cedula)->first();

            if (!$paciente) {
                // Si buscó pero no encontró, enviamos mensaje de error
                session()->flash('paciente_no_encontrado', $request->cedula);
            }
        }

        return view('recepcionista.citas.create', [
            'paciente'      => $paciente,
            // Normalizamos traer doctores con rol 2 (según lógica anterior de buscarPaciente)
            'doctores'      => User::where('rol', 2)->get(),
            'especialidades' => Especialidades::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'doctor_id' => 'required|exists:usuarios,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'fecha_inicio' => 'required|date',
            'motivo' => 'nullable|string|max:255',
        ]);

        $cita=Citas::create([
            'paciente_id' => $request->paciente_id,
            'doctor_id' => $request->doctor_id,
            'especialidad_id' => $request->especialidad_id,
            'fecha_inicio' => $request->fecha_inicio,
            'estado' => 'pendiente',
            'motivo' => $request->motivo
        ]);

        auditar(
            'INSERT',
            'citas',
            $cita->id,
            null,
            $cita->toArray()
        );
        return redirect()
            ->route('citas.create')
            ->with('success', 'Cita agendada correctamente');
    }
}
