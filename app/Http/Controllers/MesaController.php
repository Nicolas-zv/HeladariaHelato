<?php

// app/Http/Controllers/MesaController.php
namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    public function index()
    {
        $mesas = Mesa::orderBy('numero')->paginate(10);
        return view('mesas.index', compact('mesas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'numero'    => 'required|integer|min:1|unique:mesas,numero',
            'capacidad' => 'required|integer|min:1|max:20',
            'ubicacion' => 'nullable|string|max:100',
            'estado'    => 'required|in:disponible,ocupada,reservada,inactiva',
        ]);
        Mesa::create($data);

        return redirect()->route('mesas.index')
            ->with('ok', 'Mesa creada correctamente.');
    }

    public function show(Mesa $mesa)
    {
        // Para uso vía fetch (opcional). Si no lo usas, puedes omitir.
        return response()->json($mesa);
    }

    public function edit(Mesa $mesa)
    {
        // No se usa porque editamos por modal en el index
        abort(404);
    }

    public function update(Request $request, Mesa $mesa)
    {
        $data = $request->validate([
            'numero'    => 'required|integer|min:1|unique:mesas,numero,' . $mesa->id,
            'capacidad' => 'required|integer|min:1|max:20',
            'ubicacion' => 'nullable|string|max:100',
            'estado'    => 'required|in:disponible,ocupada,reservada,inactiva',
        ]);
        $mesa->update($data);

        return redirect()->route('mesas.index')
            ->with('ok', 'Mesa actualizada.');
    }

    public function destroy(Mesa $mesa)
    {
        $mesa->delete();

        return redirect()->route('mesas.index')
            ->with('ok', 'Mesa eliminada.');
    }
}
