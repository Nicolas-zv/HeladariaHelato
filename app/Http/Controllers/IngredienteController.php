<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use App\Models\Item; // Clase Padre
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngredienteController extends Controller
{
    /**
     * Muestra un listado del recurso.
     */
    public function index(Request $request)
    {
        // Se carga la relación 'item' (eager loading)
        $query = Ingrediente::with('item');

        
        $ingredientes = $query->select('ingredientes.*') // Selecciona solo las columnas de Ingrediente para evitar colisiones
            ->join('items', 'ingredientes.item_id', '=', 'items.id')
            ->orderBy('items.nombre') // Ordena por el nombre del Item
            ->paginate(15);
        
        return view('ingredientes.index', compact('ingredientes'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        return view('ingredientes.create');
    }

    /**
     * Almacena un recurso recién creado en el almacenamiento (Guarda en Item y Ingrediente).
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // Validación de Item
            'codigo' => 'required|string|max:255|unique:items,codigo',
            'nombre' => 'required|string|max:255',
            
            // Validación de Ingrediente
            'unidad' => 'nullable|string|max:50',
            'stock' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 1. Crear el Item Padre
            $item = Item::create([
                'codigo' => $validatedData['codigo'],
                'nombre' => $validatedData['nombre'],
                'disponible' => true,
                'cantidad' => 0, 
            ]);

            // 2. Crear el Ingrediente Hijo y ligarlo al Item
            Ingrediente::create([
                'item_id' => $item->id,
                'unidad' => $validatedData['unidad'],
                'stock' => $validatedData['stock'],
            ]);

            DB::commit();
            return redirect()->route('ingredientes.index')->with('success', 'Ingrediente creado (Item y Subclase).');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['db_error' => 'Error al guardar el ingrediente: ' . $e->getMessage()]);
        }
    }

    /**
     * Muestra un ingrediente específico (CU22: Consultar Factura).
     */
    public function show(Ingrediente $ingrediente)
    {

        $ingrediente->load('item'); 
        
        return view('ingredientes.show', compact('ingrediente'));
    }


    /**
     * Muestra el formulario para editar el recurso especificado.
     */
    public function edit(Ingrediente $ingrediente)
    {
        $ingrediente->load('item');
        return view('ingredientes.edit', compact('ingrediente'));
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento (Actualiza Item y Ingrediente).
     */
    public function update(Request $request, Ingrediente $ingrediente)
    {

        $itemId = $ingrediente->item_id ?? null;

        $validatedData = $request->validate([
            // Validación de Item (Código único ignorando el Item actual)
            'codigo' => 'required|string|max:255|unique:items,codigo,' . $itemId,
            'nombre' => 'required|string|max:255',
            
            // Validación de Ingrediente
            'unidad' => 'nullable|string|max:50',
            'stock' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            // 1. Actualizar el Item Padre
            $ingrediente->item->update([
                'codigo' => $validatedData['codigo'],
                'nombre' => $validatedData['nombre'],
            ]);

            // 2. Actualizar el Ingrediente Hijo
            $ingrediente->update([
                'unidad' => $validatedData['unidad'],
                'stock' => $validatedData['stock'],
            ]);

            DB::commit();
            return redirect()->route('ingredientes.index')->with('success', 'Ingrediente actualizado.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['db_error' => 'Error al actualizar el ingrediente.']);
        }
    }

    /**
     * Elimina el recurso especificado del almacenamiento (Elimina Ingrediente e Item).
     */
    public function destroy(Ingrediente $ingrediente)
    {
        try {
            $item = $ingrediente->item; 

            // 1. Eliminar Ingrediente (Hijo)
            // Esto es redundante si item_id tiene cascadeOnDelete(),
            // pero lo dejamos para seguridad si el cascade no está bien configurado en la DB.
            $ingrediente->delete();
            
            // 2. Eliminar el Item Padre asociado
            if ($item) {
                // Si la migración de 'ingredientes' tiene 'cascadeOnDelete()' en item_id, 
                // esto aseguraría que otros hijos de Item (ej. Productos) no existan, 
                // pero Item se eliminará con éxito si no tiene más referencias.
                $item->delete();
            }

            return redirect()->route('ingredientes.index')->with('success', 'Ingrediente e Item base eliminados exitosamente.');

        } catch (\Exception $e) {
            // Este error suele ser una violación de FK si el ingrediente se usa en una receta o producto.
            return redirect()->route('ingredientes.index')->with('error', 'Error: No se pudo eliminar el ingrediente. Podría estar siendo utilizado en alguna receta o stock.');
        }
    }
}