<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Paciente;

class AdminController extends Controller
{
    //Editar perfil Admin
    public function editProfile()
    {
        $user = Auth::user();
        return view('admin.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nombre' => 'required|string|max:100',
            'email'  => 'required|email|max:100|unique:usuarios,email,' . $user->id,
            'tel'    => 'nullable|string|max:10',
        ]);

        $user->update([
            'nombre' => $request->nombre,
            'email'  => $request->email,
            'tel'    => $request->tel,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Perfil actualizado correctamente');
    }

    //Pacientes
    public function pacientesIndex(Request $request)
    {
        $pacientes = Paciente::orderBy('nombres')->get();

        $pacienteSeleccionado = null;
        if ($request->has('paciente')) {
            $pacienteSeleccionado = Paciente::find($request->paciente);
        }

        return view('admin.pacientes.index', compact(
            'pacientes',
            'pacienteSeleccionado'
        ));
    }

    public function pacientesCreate()
    {
        return view('admin.pacientes.create');
    }

    public function pacientesStore(Request $request)
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

        return redirect()
            ->route('admin.pacientes.index')
            ->with('success', 'Paciente registrado correctamente');
    }

    public function pacientesUpdate(Request $request, Paciente $paciente)
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

        return redirect()
            ->route('admin.pacientes.index')
            ->with('success', 'Paciente actualizado correctamente');
    }

    public function pacientesCitas(Paciente $paciente)
    {
        $paciente->load([
            'citas.especialidad',
            'citas.doctor'
        ]);

        return view('admin.pacientes.citas', compact('paciente'));
    }

    //Usuarios
    public function usuariosIndex()
    {
        $usuarios = User::orderBy('nombre')->get();

        return view('admin.usuarios.index', compact('usuarios'));
    }
}
