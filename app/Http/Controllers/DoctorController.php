<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Paciente;
use App\Models\Citas;
use App\Models\Especialidades;
use App\Models\HistoriaClinica;
use App\Rules\ValidarCedulaEcuatoriana;
use Illuminate\Database\QueryException;



class DoctorController extends Controller
{
    // LISTAR DOCTORES
    public function index()
    {
        $doctores = User::where('rol', 0)->orderBy('nombre')->get();
        return view('admin.doctores.index', compact('doctores'));
    }
    //Perfil
    public function editProfile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('doctor.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $antes = $user->toArray(); // 🔴 ANTES

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

        // ✅ AUDITORÍA
        auditar(
            'UPDATE',
            'usuarios',
            $user->id,
            $antes,
            $user->fresh()->toArray()
        );

        return redirect()
            ->route('doctor.dashboard')
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

        return view('doctor.paciente.index', compact(
            'pacientes',
            'pacienteSeleccionado'
        ));
    }

    public function pacientesCreate()
    {
        return view('doctor.paciente.create');
    }

    public function pacientesStore(Request $request)
    {
        $request->validate([
            'cedula' => ['required', 'string', 'max:20', new ValidarCedulaEcuatoriana],
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email' => 'nullable|email',
            'telefono' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'nullable|string',
            'consentimiento_lopdp' => 'required|accepted',
        ]);

        try {
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
                ->route('doctor.pacientes.index')
                ->with('success', 'Paciente registrado correctamente');
        } catch (QueryException $e) {
            $errorMessage = $e->errorInfo[2] ?? 'Error desconocido';

            if (str_contains($errorMessage, 'no cumple la edad mínima')) {
                return back()
                    ->withErrors(['fecha_nacimiento' => 'El paciente debe tener al menos 1 año de edad.'])
                    ->withInput();
            }

            if (str_contains($errorMessage, 'fecha de nacimiento no puede ser futura')) {
                return back()
                    ->withErrors(['fecha_nacimiento' => 'La fecha no puede ser futura.'])
                    ->withInput();
            }

            if (str_contains($errorMessage, 'El correo electrónico') && str_contains($errorMessage, 'ya está registrado')) {
                return back()
                    ->withErrors(['email' => 'Este correo ya está registrado en el sistema.'])
                    ->withInput();
            }

            if (str_contains($errorMessage, 'pacientes_cedula_unique') || str_contains($errorMessage, 'cedula')) {
                return back()
                    ->withErrors(['cedula' => 'Esta cédula ya está registrada.'])
                    ->withInput();
            }

            return back()
                ->with('error', 'Error de base de datos: ' . $errorMessage)
                ->withInput();
        }
    }

    public function pacientesUpdate(Request $request, Paciente $paciente)
    {
        $antes = $paciente->toArray();

        $request->validate([
            'telefono' => 'required|string|max:10',
            'email' => 'nullable|email',
            'direccion' => 'nullable|string',
        ]);

        try {
            $paciente->update([
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
            ]);

            // ✅ AUDITORÍA
            auditar(
                'UPDATE',
                'pacientes',
                $paciente->id,
                $antes,
                $paciente->fresh()->toArray()
            );

            return redirect()
                ->route('doctor.pacientes.index')
                ->with('success', 'Paciente actualizado correctamente');
        } catch (QueryException $e) {
            $errorMessage = $e->errorInfo[2] ?? 'Error desconocido';

            if (str_contains($errorMessage, 'El correo electrónico') && str_contains($errorMessage, 'ya está registrado')) {
                return back()
                    ->withErrors(['email' => 'Este correo ya está registrado por otro paciente.'])
                    ->withInput();
            }

            return back()
                ->with('error', 'Error al actualizar: ' . $errorMessage)
                ->withInput();
        }
    }


    public function pacientesCitas(Paciente $paciente)
    {
        $paciente->load([
            'citas.especialidad',
            'citas.doctor'
        ]);

        return view('doctor.paciente.citas', compact('paciente'));
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

        return view('doctor.citas.create', [
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

        return view('doctor.citas.create', [
            'citas' => $citas,
            'paciente' => null,
            'doctores' => User::where('rol', 0)->get(),
            'especialidades' => Especialidades::all()
        ]);
    }

    public function citasUpdate(Request $request, Citas $cita)
    {
        $request->validate([
            'doctor_id'       => 'required|exists:usuarios,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'fecha_inicio'    => 'required|date',
            'estado'          => 'required|in:pendiente,confirmada,cancelada',
            'motivo'          => 'nullable|string|max:255',
        ]);

        $antes = $cita->toArray();

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
            ->route('doctor.pacientes.citas', $cita->paciente_id);
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
            ->route('doctor.pacientes.create')
            ->with('success', 'Cita agendada correctamente');
    }

    public function historiaIndex()
    {
        $historias = HistoriaClinica::with('paciente')
            ->orderBy('fecha_atencion', 'desc')
            ->get();

        return view('doctor.historia_clinica.create', [
            'historias' => $historias,
            'paciente'  => null
        ]);
    }

    // ===============================
    // HISTORIA CLÍNICA - CREAR / BUSCAR
    // ===============================
    public function historiaCreate(Request $request)
    {
        $paciente = null;

        if ($request->has('cedula') && $request->cedula != null) {
            $paciente = Paciente::where('cedula', $request->cedula)->first();

            if (!$paciente) {
                session()->flash('paciente_no_encontrado', $request->cedula);
            }
        }

        return view('doctor.historia_clinica.create', [
            'paciente'  => $paciente,
            'historias' => $paciente
                ? HistoriaClinica::where('paciente_id', $paciente->id)
                ->orderBy('fecha_atencion', 'desc')
                ->get()
                : collect()
        ]);
    }

    // ===============================
    // GUARDAR HISTORIA CLÍNICA
    // ===============================
    public function historiaStore(Request $request)
    {
        $request->validate([
            'paciente_id'      => 'required|exists:pacientes,id',
            'numero_historia'  => 'required|string|max:50',
            'fecha_atencion'   => 'required|date',
            'estado_historia'  => 'required|in:abierta,cerrada',
            'motivo_consulta'  => 'nullable|string',
            'enfermedad_actual' => 'nullable|string',

            // Constantes vitales
            'temperatura'             => 'nullable|string|max:10',
            'presion_arterial'        => 'nullable|string|max:10',
            'pulso'                   => 'nullable|string|max:10',
            'frecuencia_respiratoria' => 'nullable|string|max:10',

            // Examen clínico
            'labios'     => 'nullable|string',
            'lengua'     => 'nullable|string',
            'paladar'    => 'nullable|string',
            'piso_boca'  => 'nullable|string',
            'encias'     => 'nullable|string',
            'carrillos'  => 'nullable|string',
            'orofaringe' => 'nullable|string',
            'atm'        => 'nullable|string',

            'observaciones' => 'nullable|string',
        ]);

        // 🔒 Evitar 2 historias abiertas
        if (HistoriaClinica::where('paciente_id', $request->paciente_id)
            ->where('estado_historia', 'abierta')
            ->exists()
        ) {

            return back()->withErrors('El paciente ya tiene una historia clínica abierta');
        }

        $historia = HistoriaClinica::create([
            'paciente_id'       => $request->paciente_id,
            'numero_historia'   => $request->numero_historia,
            'fecha_atencion'    => $request->fecha_atencion,
            'estado_historia'   => $request->estado_historia,
            'motivo_consulta'   => $request->motivo_consulta,
            'enfermedad_actual' => $request->enfermedad_actual,

            // Antecedentes personales
            'alergias'             => $request->alergias,
            'cardiopatias'         => $request->boolean('cardiopatias'),
            'diabetes'             => $request->boolean('diabetes'),
            'hipertension'         => $request->boolean('hipertension'),
            'tuberculosis'         => $request->boolean('tuberculosis'),
            'antecedentes_otros'   => $request->antecedentes_otros,

            // Antecedentes familiares
            'fam_diabetes'     => $request->boolean('fam_diabetes'),
            'fam_hipertension' => $request->boolean('fam_hipertension'),
            'fam_cancer'       => $request->boolean('fam_cancer'),
            'fam_tuberculosis' => $request->boolean('fam_tuberculosis'),

            // Constantes vitales
            'temperatura'             => $request->temperatura,
            'presion_arterial'        => $request->presion_arterial,
            'pulso'                   => $request->pulso,
            'frecuencia_respiratoria' => $request->frecuencia_respiratoria,

            // Examen clínico
            'labios'     => $request->labios,
            'lengua'     => $request->lengua,
            'paladar'    => $request->paladar,
            'piso_boca'  => $request->piso_boca,
            'encias'     => $request->encias,
            'carrillos'  => $request->carrillos,
            'orofaringe' => $request->orofaringe,
            'atm'        => $request->atm,

            // Observaciones
            'observaciones' => $request->observaciones,

            // Auditoría
            'profesional_id' => Auth::id(),
        ]);

        auditar(
            'INSERT',
            'historias_clinicas',
            $historia->id,
            null,
            $historia->toArray()
        );

        return redirect()
            ->route('doctor.pacientes.index')
            ->with('success', 'Historia clínica creada correctamente');
    }
    // ===============================
    // VER HISTORIAS CLÍNICAS DEL PACIENTE
    // ===============================
    public function pacienteHistorias(Paciente $paciente)
    {
        $paciente->load([
            'historiasClinicas',
            'historiasClinicas.paciente',
        ]);

        return view('doctor.paciente.historia_clinica', compact('paciente'));
    }

    // FORM CREAR
    public function create()
    {
        return view('admin.doctores.create');
    }

    // GUARDAR
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email',
            'tel' => 'nullable|string|max:20',
            'password' => 'required|min:6',
        ]);

        $doctor = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'tel' => $request->tel,
            'password' => Hash::make($request->password),
            'rol' => 0,
            'estado' => 1,
            'two_factor_enabled' => false,
        ]);

        auditar(
            'INSERT',
            'usuarios',
            $doctor->id,
            null,
            $doctor->toArray()
        );


        return redirect()
            ->route('admin.doctores.index')
            ->with('success', 'Doctor creado correctamente');
    }

    //Crear Historial Clinico

    // FORM EDITAR
    public function edit(User $doctor)
    {
        return view('admin.doctores.edit', compact('doctor'));
    }

    // ACTUALIZAR
    public function update(Request $request, User $doctor)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email,' . $doctor->id,
            'tel' => 'nullable|string|max:20',
        ]);

        $doctor->update($request->only('nombre', 'email', 'tel'));

        return redirect()
            ->route('admin.doctores.index')
            ->with('success', 'Doctor actualizado correctamente');
    }
}
