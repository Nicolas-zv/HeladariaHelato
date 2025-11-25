<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Item;
use App\Models\Producto;
use App\Models\Ingrediente;
use App\Models\Tipo;
use App\Models\Sabor;
use App\Models\Menu;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ordenar por el nombre del Item (tabla items) ya que productos no tiene columna 'nombre'
        $productos = Producto::select('productos.*')
            ->join('items', 'items.id', '=', 'productos.item_id')
            ->with(['tipo', 'sabor', 'item'])
            ->orderBy('items.nombre')
            ->paginate(15);

        $tipos = Tipo::all();
        $sabores = Sabor::all();
        $menus = Menu::all();

        return view('productos.index', compact('productos', 'tipos', 'sabores', 'menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = Tipo::all();
        $sabores = Sabor::all();
        $menus = Menu::all();
        $ingredientes = Ingrediente::with('item')->orderBy('item_id')->get();
        $receta_data = [];

        return view('productos.create', compact('tipos', 'sabores', 'menus', 'ingredientes', 'receta_data'));
    }

    /**
     * Store a newly created resource in storage (Guarda en Item, Producto y Receta).
     */
    public function store(Request $request)
    {
        // Reglas base (no usamos Rule::unique('productos','nombre') porque productos no tiene 'nombre')
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string|max:255|unique:items,codigo',
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'tipo_id' => 'required|exists:tipos,id',
            'sabor_id' => 'nullable|exists:sabores,id',
            'menu_id' => 'nullable|exists:menus,id',
            'descripcion' => 'nullable|string',
            'receta' => 'nullable|array',
            'receta.*.ingrediente_id' => 'required_with:receta|exists:ingredientes,id',
            'receta.*.cantidad' => 'required_with:receta|numeric|min:0.001',
        ]);

        // Validación adicional: unicidad del nombre por tipo buscando en items
        $validator->after(function ($v) use ($request) {
            if ($request->filled('nombre') && $request->filled('tipo_id')) {
                $exists = Producto::where('tipo_id', $request->tipo_id)
                    ->whereHas('item', function ($q) use ($request) {
                        $q->where('nombre', $request->nombre);
                    })
                    ->exists();

                if ($exists) {
                    $v->errors()->add('nombre', 'El nombre ya está registrado para ese tipo.');
                }
            }
        });

        $validated = $validator->validate();

        try {
            DB::beginTransaction();

            // 1) Crear Item (padre)
            $item = Item::create([
                'codigo' => $validated['codigo'],
                'nombre' => $validated['nombre'],
                'disponible' => true,
                'cantidad' => 0,
            ]);

            // 2) Crear Producto (tabla productos NO tiene campo 'nombre')
            $producto = Producto::create([
                'item_id'    => $item->id,
                'precio'     => $validated['precio'],
                'tipo_id'    => $validated['tipo_id'],
                'sabor_id'   => $validated['sabor_id'] ?? null,
                'menu_id'    => $validated['menu_id'] ?? null,
                'descripcion'=> $validated['descripcion'] ?? null,
            ]);

            // 3) Sincronizar receta
            $this->syncReceta($producto, $validated['receta'] ?? []);

            DB::commit();

            return redirect()->route('productos.index')->with('success', 'Producto y receta creados exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['db_error' => 'Error al guardar el producto. Detalle: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $tipos = Tipo::all();
        $sabores = Sabor::all();
        $menus = Menu::all();

        $ingredientes = Ingrediente::with('item')->orderBy('item_id')->get();

        // Cargar la receta actual
        $producto->load(['ingredientes.item']);

        $receta_data = $producto->ingredientes
            ->map(function ($ingrediente) {
                return [
                    'ingrediente_id' => $ingrediente->id,
                    'cantidad' => $ingrediente->pivot->cantidad,
                    'unidad' => $ingrediente->pivot->unidad,
                ];
            })
            ->toArray();

        $receta_data = old('receta', $receta_data);

        return view('productos.edit', compact('producto', 'tipos', 'sabores', 'menus', 'ingredientes', 'receta_data'));
    }

    /**
     * Update the specified resource in storage (Actualiza Item, Producto y Receta).
     */
     // Método update para ProductoController
    public function update(Request $request, Producto $producto)
    {
        // Usamos Validator para poder hacer la comprobación personalizada de unicidad (nombre en items)
        $validator = Validator::make($request->all(), [
            // Validación de Item (Código único ignorando el Item actual)
            'codigo' => 'required|string|max:255|unique:items,codigo,' . ($producto->item_id ?? 'NULL'),
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'tipo_id' => 'required|exists:tipos,id',
            'sabor_id' => 'nullable|exists:sabores,id',
            'menu_id' => 'nullable|exists:menus,id',
            'descripcion' => 'nullable|string',
            'receta' => 'nullable|array',
            'receta.*.ingrediente_id' => 'required_with:receta|exists:ingredientes,id',
            'receta.*.cantidad' => 'required_with:receta|numeric|min:0.001',
        ]);

        // Comprobación de unicidad "nombre por tipo" excluyendo el producto actual
        $validator->after(function ($v) use ($request, $producto) {
            if ($request->filled('nombre') && $request->filled('tipo_id')) {
                $exists = Producto::where('tipo_id', $request->tipo_id)
                    ->where('id', '!=', $producto->id)
                    ->whereHas('item', function ($q) use ($request) {
                        $q->where('nombre', $request->nombre);
                    })
                    ->exists();

                if ($exists) {
                    $v->errors()->add('nombre', 'El nombre ya está registrado para ese tipo.');
                }
            }
        });

        $validated = $validator->validate();

        try {
            DB::beginTransaction();

            // 1) Actualizar o crear el Item Padre asociado
            if ($producto->item) {
                $producto->item->update([
                    'codigo' => $validated['codigo'],
                    'nombre' => $validated['nombre'],
                ]);
            } else {
                // Inconsistencia: crear item y asociarlo
                $item = Item::create([
                    'codigo' => $validated['codigo'],
                    'nombre' => $validated['nombre'],
                    'disponible' => true,
                    'cantidad' => 0,
                ]);
                $producto->item_id = $item->id;
                $producto->save();
            }

            // 2) Actualizar campos del producto (tabla productos NO tiene 'nombre')
            $producto->update([
                'precio' => $validated['precio'],
                'tipo_id' => $validated['tipo_id'],
                'sabor_id' => $validated['sabor_id'] ?? null,
                'menu_id' => $validated['menu_id'] ?? null,
                'descripcion' => $validated['descripcion'] ?? null,
            ]);

            // 3) Sincronizar la receta (usa la función syncReceta existente)
            $this->syncReceta($producto, $validated['receta'] ?? []);

            DB::commit();

            return redirect()->route('productos.index')->with('success', 'Producto y receta actualizados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['db_error' => 'Error al actualizar el producto. Detalle: '.$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage (Elimina Producto e Item).
     */
    public function destroy(Producto $producto)
    {
        try {
            DB::beginTransaction();

            $item = $producto->item;

            // 1. Eliminar Producto
            $producto->delete();

            // 2. Eliminar el Item Padre asociado
            if ($item) {
                $item->delete();
            }

            DB::commit();
            return redirect()->route('productos.index')->with('success', 'Producto e Item base eliminados exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('productos.index')->with('error', 'Error al eliminar el producto. Asegúrese de que no esté relacionado con otras tablas.');
        }
    }

    /**
     * Helper: Lógica para sincronizar la receta.
     *
     * Mantengo una sola implementación de syncReceta PARA EVITAR DUPLICADOS.
     */
    private function syncReceta(Producto $producto, ?array $recetaData)
    {
        $syncData = [];

        if (!empty($recetaData)) {
            // Obtener unidades de los ingredientes en un solo query
            $ingredientesUnidades = Ingrediente::pluck('unidad', 'id')->toArray();

            foreach ($recetaData as $item) {
                $ingredienteId = $item['ingrediente_id'] ?? null;
                $cantidad = $item['cantidad'] ?? null;

                if (!$ingredienteId || $cantidad === null) {
                    continue;
                }

                $syncData[$ingredienteId] = [
                    'cantidad' => $cantidad,
                    'unidad' => $ingredientesUnidades[$ingredienteId] ?? null,
                ];
            }
        }

      
        $producto->ingredientes()->sync($syncData);
    }
}