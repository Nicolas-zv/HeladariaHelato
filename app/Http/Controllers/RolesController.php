<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class RolesController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Listado
     */
    public function index()
    {
        // Si usas multi-guard, ajusta 'web'
        $roles = Role::query()->where('guard_name', 'web')->orderBy('name')->paginate(10);
        return view('roles.index', compact('roles'));
    }

    /**
     * Form nuevo
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Guardar
     */
    public function store(Request $request)
    {
        $request->validate([
            // La tabla es 'roles' (de Spatie) con unique por (name, guard_name)
            'name' => [
                'required','string','max:255',
                Rule::unique('roles', 'name')->where('guard_name', 'web'),
            ],
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
    }

    /**
     * Editar
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * Actualizar
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => [
                'required','string','max:255',
                Rule::unique('roles', 'name')
                    ->where('guard_name', 'web')
                    ->ignore($role->id),
            ],
        ]);

        $role->update([
            'name' => $request->name,
            // guard_name no lo cambiamos normalmente
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    /**
     * Eliminar
     */
    public function destroy(Role $role)
    {
        // Opcional: evita borrar roles críticos
        if (in_array($role->name, ['admin','superadmin'])) {
            return back()->with('error', 'No puedes eliminar este rol.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }
}
