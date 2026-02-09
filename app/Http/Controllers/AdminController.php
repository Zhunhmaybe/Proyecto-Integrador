<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Paciente;
use App\Models\Citas;
use App\Models\Especialidades;




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

        $antes = $user->toArray();

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

        auditar(
            'UPDATE',
            'usuarios',
            $user->id,
            $antes,
            $user->fresh()->toArray()
        );
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

        $paciente = Paciente::create([
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

        // ✅ AUDITORÍA
        auditar(
            'INSERT',
            'pacientes',
            $paciente->id,
            null,
            $paciente->toArray()
        );
        return redirect()
            ->route('admin.pacientes.index')
            ->with('success', 'Paciente registrado correctamente');
    }

    public function pacientesUpdate(Request $request, Paciente $paciente)
    {
        $antes = $paciente->toArray();
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

        auditar(
            'UPDATE',
            'pacientes',
            $paciente->id,
            $antes,
            $paciente->fresh()->toArray()
        );

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

    public function usuariosEdit(User $user)
    {
        $roles = [
            0 => 'Doctor',
            1 => 'Administrador',
            2 => 'Auditor',
            3 => 'Recepcionista',
        ];

        return view('admin.usuarios.edit', compact('user', 'roles'));
    }

    public function usuariosUpdate(Request $request, User $user)
{
    $antes = $user->toArray();

    $request->validate([
        'nombre' => 'required|string|max:100',
        'email'  => 'required|email|unique:usuarios,email,' . $user->id,
        'tel'    => 'nullable|string|max:20',
        'rol'    => 'required|in:0,1,2,3',
    ]);

    $user->update([
        'nombre' => $request->nombre,
        'email'  => $request->email,
        'tel'    => $request->tel,
        'rol'    => $request->rol,
    ]);

    // ✅ AUDITORÍA DEL CAMBIO DE ROL
    auditar(
        'UPDATE',
        'usuarios',
        $user->id,
        $antes,
        $user->fresh()->toArray()
    );

    return redirect()
        ->route('admin.usuarios.index')
        ->with('success', 'Usuario actualizado y rol asignado correctamente');
}


    //Citas

    public function Admincreate(Request $request)
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

        return view('admin.citas.create', [
            'paciente'      => $paciente,
            // Normalizamos traer doctores con rol 2 (según lógica anterior de buscarPaciente)
            'doctores'      => User::where('rol', 0)->get(),
            'especialidades' => Especialidades::all()
        ]);
    }

    public function citasIndex()
    {
        $citas = Citas::with(['paciente', 'doctor', 'especialidad'])
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        return view('admin.citas.create', compact('citas'));
    }

    public function citasEdit(Citas $cita)
    {
        $cita->load(['paciente', 'doctor', 'especialidad']);

        $doctores = User::where('rol', 0)->get(); // doctores
        $especialidades = Especialidades::all();

        return view('admin.citas.edit', compact(
            'cita',
            'doctores',
            'especialidades'
        ));
    }

    public function citasUpdate(Request $request, Citas $cita)
    {
        $antes = $cita->toArray();
        $request->validate([
            'doctor_id'       => 'required|exists:usuarios,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'fecha_inicio'    => 'required|date',
            'estado'          => 'required|in:pendiente,confirmada,cancelada',
            'motivo'          => 'nullable|string|max:255',
        ]);

        $cita->update([
            'doctor_id'       => $request->doctor_id,
            'especialidad_id' => $request->especialidad_id,
            'fecha_inicio'    => $request->fecha_inicio,
            'estado'          => $request->estado,
            'motivo'          => $request->motivo,
        ]);

        auditar(
            'UPDATE',
            'citas',
            $cita->id,
            $antes,
            $cita->fresh()->toArray()
        );
        return redirect()
            ->route('admin.pacientes.citas', $cita->paciente_id);
    }

    public function Adminstore(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'doctor_id' => 'required|exists:usuarios,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'fecha_inicio' => 'required|date',
            'motivo' => 'nullable|string|max:255',
        ]);

        $cita = Citas::create([
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
            ->route('admin.citas.create')
            ->with('success', 'Cita agendada correctamente');
    }
    //Roles
    public function rolesIndex()
    {
        // Agrupar usuarios por rol
        $roles = User::select('rol')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('rol')
            ->orderBy('rol')
            ->get();

        return view('admin.roles.index', compact('roles'));
    }
}
