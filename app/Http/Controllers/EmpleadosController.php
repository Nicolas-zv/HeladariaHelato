<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class EmpleadosController extends Controller
{
    /**
     * Mostrar listado de empleados con búsqueda simple.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Empleado::with('persona', 'usuario');

        if ($q) {
            $query->whereHas('persona', function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('carnet', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%");
            })->orWhere('direccion', 'like', "%{$q}%");
        }

        $empleados = $query->orderByDesc('id')->paginate(12)->withQueryString();

        return view('empleados.index', compact('empleados', 'q'));
    }

    /**
     * Mostrar formulario para crear nuevo empleado.
     * Se permite elegir una Persona existente o crear una persona nueva inline.
     */
    public function create()
    {
        $personas = Persona::orderBy('nombre')->limit(200)->get(); // lista para select
        $users = User::orderBy('name')->limit(200)->get(); // opcional relacion a usuario para login

        return view('empleados.create', compact('personas', 'users'));
    }

    /**
     * Guardar nuevo empleado (puede crear persona si no se envía persona_id).
     *
     * Payload esperado:
     * - persona_id (opcional) OR nombre (+ carnet, telefono)
     * - direccion (opcional)
     * - usuario_id (opcional)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'persona_id' => ['nullable', 'integer', 'exists:personas,id'],
            'nombre' => ['required_without:persona_id', 'string', 'max:255'],
            'carnet' => ['nullable', 'string', 'max:50'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'users_id' => ['nullable', 'integer', 'exists:users,id'], // <- users_id
        ]);

        DB::beginTransaction();
        try {
            if (empty($data['persona_id'])) {
                $persona = Persona::create([
                    'nombre' => $data['nombre'],
                    'carnet' => $data['carnet'] ?? null,
                    'telefono' => $data['telefono'] ?? null,
                ]);
            } else {
                $persona = Persona::findOrFail($data['persona_id']);
            }

            $empleado = Empleado::create([
                'persona_id' => $persona->id,
                'direccion' => $data['direccion'] ?? null,
                'users_id' => $data['users_id'] ?? null, // <- users_id guardado aquí
            ]);

            DB::commit();
            return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al crear empleado: ' . $e->getMessage()]);
        }
    }


    /**
     * Mostrar formulario para editar empleado.
     * Permitimos editar datos de la Persona asociada y campos del Empleado.
     */
    public function edit(Empleado $empleado)
    {
        $empleado->load('persona', 'usuario');
        $personas = Persona::orderBy('nombre')->limit(200)->get();
        $users = User::orderBy('name')->limit(200)->get();

        return view('empleados.edit', compact('empleado', 'personas', 'users'));
    }

    /**
     * Actualizar empleado y (si viene) datos de la persona.
     */
    public function update(Request $request, Empleado $empleado)
    {
        $data = $request->validate([
            'persona_id' => ['nullable', 'integer', 'exists:personas,id'],
            'nombre' => ['required_without:persona_id', 'string', 'max:255'],
            'carnet' => ['nullable', 'string', 'max:50'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'users_id' => ['nullable', 'integer', 'exists:users,id'], // <- users_id
        ]);

        DB::beginTransaction();
        try {
            if (!empty($data['persona_id']) && $data['persona_id'] != $empleado->persona_id) {
                $empleado->persona_id = $data['persona_id'];
            } else {
                $empleado->persona->update([
                    'nombre' => $data['nombre'] ?? $empleado->persona->nombre,
                    'carnet' => $data['carnet'] ?? $empleado->persona->carnet,
                    'telefono' => $data['telefono'] ?? $empleado->persona->telefono,
                ]);
            }

            $empleado->direccion = $data['direccion'] ?? $empleado->direccion;
            $empleado->users_id = $data['users_id'] ?? null; // <- asignación correcta
            $empleado->save();

            DB::commit();
            return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al actualizar empleado: ' . $e->getMessage()]);
        }
    }

    /**
     * Eliminar empleado (no elimina la Persona por seguridad).
     */
    public function destroy(Empleado $empleado)
    {
        try {
            $empleado->delete();
            return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Error al eliminar empleado: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar (opcional) detalles del empleado (útil para fetch/AJAX).
     */
    public function show(Empleado $empleado)
    {
        $empleado->load('persona', 'usuario');
        return view('empleados.show', compact('empleado'));
    }
}
