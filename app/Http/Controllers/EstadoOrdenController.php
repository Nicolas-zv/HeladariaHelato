<?php

namespace App\Http\Controllers;

use App\Models\EstadoOrden;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EstadoOrdenController extends Controller
{
    /**
     * Mostrar listado paginado con búsqueda.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = EstadoOrden::query()->withCount('ordenes');

        if ($q) {
            $query->where('nombre', 'like', "%{$q}%");
        }

        $estados = $query->orderBy('nombre')->paginate(15)->withQueryString();

        return view('estado_ordenes.index', compact('estados', 'q'));
    }

    /**
     * Formulario para crear nuevo estado.
     */
    public function create()
    {
        return view('estado_ordenes.create');
    }

    /**
     * Guardar nuevo estado.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:120', 'unique:estado_ordenes,nombre'],
        ]);

        EstadoOrden::create($data);

        return redirect()->route('estado_ordenes.index')->with('success', 'Estado creado correctamente.');
    }

    /**
     * Mostrar un estado (detalle + órdenes asociadas).
     */
    public function show(EstadoOrden $estadoOrden)
    {
        $estadoOrden->load(['ordenes' => function($q) {
            $q->latest()->limit(50);
        }, 'ordenes.cliente', 'ordenes.empleado']);

        return view('estado_ordenes.show', compact('estadoOrden'));
    }

    /**
     * Formulario para editar estado.
     */
    public function edit(EstadoOrden $estadoOrden)
    {
        return view('estado_ordenes.edit', compact('estadoOrden'));
    }

    /**
     * Actualizar estado.
     */
    public function update(Request $request, EstadoOrden $estadoOrden)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:120', Rule::unique('estado_ordenes', 'nombre')->ignore($estadoOrden->id)],
        ]);

        $estadoOrden->update($data);

        return redirect()->route('estado_ordenes.index')->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Eliminar estado.
     * Previene borrado si existen órdenes asociadas.
     */
    public function destroy(EstadoOrden $estadoOrden)
    {
        if ($estadoOrden->ordenes()->exists()) {
            return redirect()->route('estado_ordenes.index')->withErrors(['error' => 'No se puede eliminar: existen órdenes asociadas a este estado.']);
        }

        $estadoOrden->delete();

        return redirect()->route('estado_ordenes.index')->with('success', 'Estado eliminado correctamente.');
    }
}