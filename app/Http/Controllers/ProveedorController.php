<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Proveedor::query();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('codigo', 'like', "%{$q}%")
                    ->orWhere('descripcion', 'like', "%{$q}%")
                    ->orWhere('ubicacion', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%");
            });
        }

        $proveedores = $query->orderBy('codigo')->paginate(12)->withQueryString();

        return view('proveedores.index', compact('proveedores', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:50', 'unique:proveedores,codigo'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
        ]);

        Proveedor::create($data);

        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        return view('proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:50', Rule::unique('proveedores', 'codigo')->ignore($proveedor->id)],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
        ]);

        $proveedor->update($data);

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado correctamente.');
    }
}