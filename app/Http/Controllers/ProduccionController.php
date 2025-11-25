<?php

namespace App\Http\Controllers;

use App\Models\Produccion;
use App\Models\Producto;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Agregamos Log para errores

class ProduccionController extends Controller
{
    // Muestra el formulario para registrar la producción
    public function create()
    {
        // Solo mostramos productos que son "producibles"
        $productos = Producto::with('item')->get(); 
        return view('produccion.create', compact('productos'));
    }

    /**
     * Ejecuta la transacción de producción y calcula el costo.
     */
    public function store(Request $request)
    {
        // Validar la solicitud (mínimo)
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad_producir' => 'required|numeric|min:0.0001',
        ]);
        
        DB::beginTransaction();

        try {
            // 1. Obtener el Producto y sus Ingredientes con el Item asociado.
            // Asumimos que Producto tiene una relación 'ingredientes' que usa el pivote.
            $producto = Producto::with(['ingredientes.item'])->findOrFail($request->producto_id);
            $cantidadProducir = (float) $request->cantidad_producir;
            $costoTotalIngredientes = 0;

            // 2. Iterar sobre los ingredientes para calcular el costo y descontar stock.
            foreach ($producto->ingredientes as $ingrediente) {
                
                // --- CLAVE: La cantidad requerida viene del objeto pivot ---
                $cantidadRequerida = (float) $ingrediente->pivot->cantidad; 
                $cantidadConsumidaTotal = $cantidadRequerida * $cantidadProducir;

                // Obtener el Costo Promedio Ponderado del Item (Ingrediente)
                // Usamos el campo costo_promedio que acabamos de agregar a la tabla 'items'
                $costoUnitarioIngrediente = $ingrediente->item->costo_promedio ?? 0;
                
                // 3. Cálculo del costo total del ingrediente para esta producción
                $costoPorIngrediente = $cantidadConsumidaTotal * $costoUnitarioIngrediente;
                $costoTotalIngredientes += $costoPorIngrediente;

                // 4. Descontar el stock (Asumo que quieres hacerlo en este proceso)
                // Se verifica si hay suficiente stock antes de descontar
                if ($ingrediente->item->cantidad < $cantidadConsumidaTotal) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Stock insuficiente para el ingrediente: ' . $ingrediente->item->nombre,
                    ], 400);
                }
                
                $ingrediente->item->decrement('cantidad', $cantidadConsumidaTotal);
            }

            // 5. Registrar la producción (se asume que Produccion tiene un 'producto_id')
            $produccion = Produccion::create([
                'producto_id' => $producto->id,
                'cantidad_producida' => $cantidadProducir,
                'costo_total_ingredientes' => round($costoTotalIngredientes, 4), // Mayor precisión
                'fecha_produccion' => now(), // O la fecha que uses
            ]);
            
            // 6. Incrementar el stock del Producto terminado
            $producto->item->increment('cantidad', $cantidadProducir);


            DB::commit();

            return response()->json([
                'message' => 'Producción registrada y costo calculado.',
                'costo_unitario_final' => round($costoTotalIngredientes / $cantidadProducir, 4),
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ProduccionController::store: ' . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error al procesar la producción.'], 500);
        }
    }
}