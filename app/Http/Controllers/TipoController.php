<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;

class TipoController extends Controller
{
    /**
     * Muestra una lista de todos los tipos.
     */
    public function index()
    {
        $tipos = Tipo::orderBy('cod_tipo')->paginate(15);
        
        return view('tipos.index', compact('tipos'));
    }

    /**
     * Muestra el formulario para crear un nuevo tipo.
     */
    public function create()
    {
        return view('tipos.create');
    }

    /**
     * Almacena un nuevo tipo en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cod_tipo' => 'required|string|max:255|unique:tipos,cod_tipo',
            'descripcion' => 'nullable|string|max:255',
        ]);

        Tipo::create($request->all());

        return redirect()->route('tipos.index')
                         ->with('success', 'Tipo creado exitosamente.');
    }

    /**
     * Muestra los detalles de un tipo específico.
     */
    public function show(Tipo $tipo)
    {
        return view('tipos.show', compact('tipo'));
    }

    /**
     * Muestra el formulario para editar un tipo existente.
     */
    public function edit(Tipo $tipo)
    {
        return view('tipos.edit', compact('tipo'));
    }

    /**
     * Actualiza un tipo en la base de datos.
     */
    public function update(Request $request, Tipo $tipo)
    {
        $request->validate([
            'cod_tipo' => 'required|string|max:255|unique:tipos,cod_tipo,' . $tipo->id,
            'descripcion' => 'nullable|string|max:255',
        ]);

        $tipo->update($request->all());

        return redirect()->route('tipos.index')
                         ->with('success', 'Tipo actualizado exitosamente.');
    }

    /**
     * Elimina un tipo de la base de datos.
     */
    public function destroy(Tipo $tipo)
    {
        try {
            $tipo->delete();
            return redirect()->route('tipos.index')->with('success', 'Tipo eliminado correctamente.');
        } catch (\Exception $e) {
            // Manejar errores de clave foránea si el tipo está en uso
            return redirect()->route('tipos.index')->with('error', 'Error: No se puede eliminar este tipo porque está en uso.');
        }
    }
}