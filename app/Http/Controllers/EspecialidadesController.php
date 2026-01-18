<?php

namespace App\Http\Controllers;

use App\Models\Especialidades;
use Illuminate\Http\Request;

class EspecialidadesController extends Controller
{
    /**
     * Mostrar listado de especialidades
     */
    public function index()
    {
        $especialidades = Especialidades::orderBy('nombre')->get();

        return view('admin.especialidades.index', compact('especialidades'));
    }

    /**
     * Mostrar formulario crear
     */
    public function create()
    {
        return view('admin.especialidades.create');
    }

    /**
     * Guardar nueva especialidad
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:especialidades,nombre',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique'   => 'Esta especialidad ya existe',
        ]);

        Especialidades::create([
            'nombre' => $request->nombre,
        ]);

        return redirect()
            ->route('admin.especialidades.index')
            ->with('success', 'Especialidad creada correctamente');
    }

    /**
     * Mostrar formulario editar
     */
    public function edit($id)
    {
        $especialidad = Especialidades::findOrFail($id);

        return view('admin.especialidades.edit', compact('especialidad'));
    }

    /**
     * Actualizar especialidad
     */
    public function update(Request $request, $id)
    {
        $especialidad = Especialidades::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:especialidades,nombre,' . $especialidad->id,
        ]);

        $especialidad->update([
            'nombre' => $request->nombre,
        ]);

        return redirect()
            ->route('admin.especialidades.index')
            ->with('success', 'Especialidad actualizada correctamente');
    }

    /**
     * Eliminar especialidad
     */
    public function destroy($id)
    {
        $especialidad = Especialidades::findOrFail($id);

        // 🔒 Opcional: evitar borrar si tiene citas
        if ($especialidad->citas()->exists()) {
            return redirect()->back()
                ->withErrors('No se puede eliminar: la especialidad tiene citas asociadas');
        }

        $especialidad->delete();

        return redirect()
            ->route('admin.especialidades.index')
            ->with('success', 'Especialidad eliminada correctamente');
    }
}
