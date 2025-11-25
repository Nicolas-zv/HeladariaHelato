<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valida los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Ajusta 'users' al nombre de tu tabla de usuarios
            'password' => 'required|string|max:255',
        ]);

        // Crea un nuevo usuario utilizando el modelo User
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;

        // Encripta la contraseña antes de guardarla
        $usuario->password = Hash::make($request->password);

        // Guarda el usuario en la base de datos
        $usuario->save();

        // Asigna el rol "Mesero" por defecto
        $usuario->assignRole('Mesero');

        // Redirecciona a una página de éxito o realiza otra acción
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente con rol de Mesero.');
    }


    /**
     * Display the specified resource.
     */
    public function show(User $usuarios)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        // Obtener todos los roles disponibles
        $roles = Role::all();

        // Obtener los roles actuales del usuario
        $userRoles = $usuario->roles->pluck('name')->toArray();

        // Pasar la información del usuario y los roles a la vista
        return view('usuarios.edit', compact('usuario', 'roles', 'userRoles'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'roles' => 'required|string', // Cambiado a string, ya que es un select simple
        ]);

        // Actualizar los datos del usuario
        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Sincronizar el rol seleccionado (convertimos el string en array)
        $usuario->syncRoles([$request->roles]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente');
    }
}
