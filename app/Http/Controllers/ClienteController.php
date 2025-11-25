<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Cliente::with('persona');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('codigo', 'like', "%{$q}%")
                    ->orWhereHas('persona', function ($p) use ($q) {
                        $p->where('nombre', 'like', "%{$q}%")
                          ->orWhere('carnet', 'like', "%{$q}%")
                          ->orWhere('telefono', 'like', "%{$q}%");
                    });
            });
        }

        $clientes = $query->orderBy('codigo')->paginate(12)->withQueryString();

        return view('clientes.index', compact('clientes', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * Crea persona y cliente dentro de una transacción.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:100', 'unique:clientes,codigo'],
            'nombre' => ['required', 'string', 'max:255'],
            'carnet' => ['nullable', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:50'],
        ]);

        DB::beginTransaction();
        try {
            $persona = Persona::create([
                'nombre' => $data['nombre'],
                'carnet' => $data['carnet'] ?? null,
                'telefono' => $data['telefono'] ?? null,
            ]);

            Cliente::create([
                'codigo' => $data['codigo'],
                'persona_id' => $persona->id,
            ]);

            DB::commit();

            return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Ocurrió un error al crear el cliente.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        $cliente->load('persona');
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * The same form will allow editing client and its persona.
     */
    public function edit(Cliente $cliente)
    {
        $cliente->load('persona');
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * Actualiza cliente y persona en la misma operación.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:100', Rule::unique('clientes', 'codigo')->ignore($cliente->id)],
            'nombre' => ['required', 'string', 'max:255'],
            'carnet' => ['nullable', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:50'],
        ]);

        DB::beginTransaction();
        try {
            // actualiza o crea la persona asociada
            if ($cliente->persona) {
                $cliente->persona->update([
                    'nombre' => $data['nombre'],
                    'carnet' => $data['carnet'] ?? null,
                    'telefono' => $data['telefono'] ?? null,
                ]);
            } else {
                $persona = Persona::create([
                    'nombre' => $data['nombre'],
                    'carnet' => $data['carnet'] ?? null,
                    'telefono' => $data['telefono'] ?? null,
                ]);
                $cliente->persona_id = $persona->id;
            }

            // actualiza el cliente
            $cliente->codigo = $data['codigo'];
            $cliente->save();

            DB::commit();

            return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Ocurrió un error al actualizar el cliente.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * No eliminamos automáticamente la persona por defecto (puedes ajustar la política).
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}