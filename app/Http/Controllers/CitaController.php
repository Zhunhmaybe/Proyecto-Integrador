<?php

namespace App\Http\Controllers;

use App\Models\Citas;
use App\Models\Paciente;
use App\Models\User;
use App\Models\Especialidades;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    // Vista principal
    public function create()
    {
        return view('recepcionista.citas.create', [
            'paciente' => null,
            'doctores' => User::where('rol', '1')->get(),
            'especialidades' => Especialidades::all()
        ]);
    }

    // Buscar paciente por cédula
    public function buscarPaciente(Request $request)
    {
        $request->validate([
            'cedula' => 'required|string|max:20',
        ]);

        $paciente = Paciente::where('cedula', $request->cedula)->first();

        $especialidades = Especialidades::all();
        $doctores = User::where('rol', 2)->get(); // asumiendo rol 2 = doctor

        if (!$paciente) {
            return view('recepcionista.citas.create', [
                'paciente' => null,
                'especialidades' => $especialidades,
                'doctores' => $doctores,
                'paciente_no_encontrado' => $request->cedula
            ]);
        }


        return view('recepcionista.citas.create', compact(
            'paciente',
            'especialidades',
            'doctores'
        ));
    }

    // Guardar cita
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'doctor_id' => 'required|exists:usuarios,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'motivo' => 'nullable|string|max:255',
        ]);

        Citas::create([
            'paciente_id' => $request->paciente_id,
            'doctor_id' => $request->doctor_id,
            'especialidad_id' => $request->especialidad_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => 'pendiente',
            'motivo' => $request->motivo
        ]);

        return redirect()->back()->with('success', 'Cita agendada correctamente');
    }
}
