<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $pacientes = Paciente::orderBy('nombres')->get();

        $pacienteSeleccionado = null;

        if ($request->has('paciente')) {
            $pacienteSeleccionado = Paciente::find($request->paciente);
        }

        return view('recepcionista.paciente.index', compact(
            'pacientes',
            'pacienteSeleccionado'
        ));
    }

    public function create()
    {
        return view('recepcionista.paciente.create');
    }

    // Guardar paciente
    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|string|max:20|unique:pacientes,cedula',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email' => 'nullable|email',
            'telefono' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'nullable|string',
            'consentimiento_lopdp' => 'required|accepted',
        ]);

        Paciente::create([
            'cedula' => $request->cedula,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'direccion' => $request->direccion,
            'consentimiento_lopdp' => true,
            'fecha_firma_lopdp' => now(),
        ]);

        return redirect()->back()->with('success', 'Paciente registrado correctamente');
    }
    public function update(Request $request, Paciente $paciente)
    {
        $request->validate([
            'telefono' => 'required|string|max:10',
            'email' => 'nullable|email',
            'direccion' => 'nullable|string',
        ]);

        $paciente->update([
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
        ]);

        return redirect()->back()->with('success', 'Paciente actualizado correctamente');
    }
    public function citas(Paciente $paciente)
{
    $paciente->load([
        'citas.especialidad',
        'citas.doctor'
    ]);

    return view('recepcionista.paciente.citas', compact('paciente'));
}

}
