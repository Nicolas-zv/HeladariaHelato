<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Producto; // Necesario para el campo 'producto_id'
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        
        $items = Item::with('producto') // Cargamos el producto relacionado
            ->when($q, function ($query, $q) {
                $query->where('codigo', 'like', '%' . $q . '%')
                      ->orWhere('nombre', 'like', '%' . $q . '%');
            })
            ->orderBy('nombre')
            ->paginate(10);

        return view('items.index', compact('items', 'q'));
    }

    public function create()
    {
        $productos = Producto::orderBy('nombre')->get();
        return view('items.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:255|unique:items,codigo',
            'nombre' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:0',
            'disponible' => 'nullable|boolean', 
            'producto_id' => 'nullable|exists:productos,id',
        ]);
        
        $data = $request->all();
        $data['disponible'] = $request->has('disponible');

        Item::create($data);

        return redirect()->route('items.index')->with('success', 'Ítem creado exitosamente.');
    }

    // ⭐ MÉTODO FALTANTE: MOSTRAR DETALLES ⭐
    public function show(Item $item)
    {
        // Se carga la relación 'producto' (aunque Laravel ya lo hace por defecto con la inyección)
        $item->load('producto'); 
        
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $productos = Producto::orderBy('nombre')->get();
        return view('items.edit', compact('item', 'productos'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'codigo' => 'required|string|max:255|unique:items,codigo,' . $item->id,
            'nombre' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:0',
            'disponible' => 'nullable|boolean',
            'producto_id' => 'nullable|exists:productos,id',
        ]);
        
        $data = $request->all();
        $data['disponible'] = $request->has('disponible');

        $item->update($data);

        return redirect()->route('items.index')->with('success', 'Ítem actualizado exitosamente.');
    }

    // ⭐ MÉTODO FALTANTE: ELIMINAR ⭐
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Ítem eliminado exitosamente.');
    }
}