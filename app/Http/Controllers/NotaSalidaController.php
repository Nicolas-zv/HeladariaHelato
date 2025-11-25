<?php

namespace App\Http\Controllers;

use App\Models\NotaSalida;
use App\Models\Item;
use Illuminate\Http\Request;

class NotaSalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salidas = NotaSalida::with('item')
            ->orderBy('fecha_hora', 'desc')
            ->paginate(15);

        return view('notas_salida.index', compact('salidas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Cargar todos los ítems para el selector (Productos e Ingredientes)
        $items = Item::all();
        
        // ¡Importante para evitar el error de variable indefinida en la vista!
        // No necesitamos $detalles_data en esta tabla, pero la definimos si la vista la pide.
        $notaSalida = new NotaSalida();
        
        return view('notas_salida.create', compact('items', 'notaSalida'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'item_id' => 'required|exists:items,id',
            'cantidad' => 'required|integer|min:1',
            'codigo' => 'nullable|string|max:255',
            'fecha_hora' => 'nullable|date',
        ]);
        
        // La lógica de stock se maneja en el Modelo (boot method)
        NotaSalida::create($validatedData);

        return redirect()->route('notas-salida.index')->with('success', 'Nota de Salida registrada y stock actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NotaSalida $notaSalida)
    {
        try {
            // La lógica de reversión de stock se maneja en el Modelo (boot method)
            $notaSalida->delete();
            return redirect()->route('notas_salida.index')->with('success', 'Nota de Salida eliminada y stock revertido.');
        } catch (\Exception $e) {
            return redirect()->route('notas_salida.index')->with('error', 'Error al eliminar la Nota de Salida.');
        }
    }
    
    // NOTA: Para NotaSalida, generalmente no se implementa el EDIT, 
    // ya que modificar una transacción de inventario es riesgoso. 
    // Se prefiere eliminar y crear una nueva.
}