<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    // LISTAR DOCTORES
    public function index()
    {
        $doctores = User::where('rol', 2)->orderBy('nombre')->get();
        return view('admin.doctores.index', compact('doctores'));
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

        User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'tel' => $request->tel,
            'password' => Hash::make($request->password),
            'rol' => 2, // 👈 DOCTOR
            'estado' => 1,
            'two_factor_enabled' => false,
        ]);

        return redirect()
            ->route('admin.doctores.index')
            ->with('success', 'Doctor creado correctamente');
    }

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
