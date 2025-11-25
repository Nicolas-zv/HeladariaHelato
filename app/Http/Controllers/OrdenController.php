<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mesa;
use App\Models\Empleado;
use App\Models\Cliente;
use App\Models\EstadoOrden;

class OrdenController extends Controller
{
    /**
     * Muestra una lista de órdenes.
     */
    public function index()
    {
        // ⭐ Cargar la relación 'estadoOrden' para mostrar el estado sin N+1 query.
        $ordenes = Orden::with(['mesa', 'empleado.persona', 'estado'])->latest()->paginate(15);

        return view('ordenes.index', compact('ordenes'));
    }

    /**
     * Muestra el formulario para crear una nueva orden.
     */
    public function create()
    {
        // ⭐ CORRECCIÓN CLAVE: Usar pluck('nombre', 'id') para crear un array asociativo [id => nombre].
        // Esto soluciona el problema de las "muchas letras" en el select de la vista.
        $estados = EstadoOrden::all()->pluck('nombre', 'id');

        $productos = Producto::with('item')
            ->select('productos.*')
            ->join('items', 'productos.item_id', '=', 'items.id')
            ->orderBy('items.nombre')
            ->get();

        $mesas = Mesa::orderBy('numero')->get();
        $empleados = Empleado::with('persona')->get();

        $clientes = Cliente::with('persona')->get();

        return view('ordenes.create', compact('productos', 'mesas', 'empleados', 'estados', 'clientes'));
    }

    /**
     * Almacena una nueva orden.
     */
    public function store(Request $request)
    {
        // 1. VALIDACIÓN DE LOS DATOS PRINCIPALES
        $validatedData = $request->validate([
            'mesa_id'           => 'required|exists:mesas,id',
            // ⭐ CAMBIO: Ahora esperamos el ID numérico, no el string de nombre.
            'estado_orden_id'   => 'required|exists:estado_ordenes,id',
            'numero_orden'      => 'required|string|max:50',
            'fecha_hora'        => 'required|date',
            'cliente_id'        => 'nullable|exists:clientes,id',
            'empleado_id'       => 'nullable|exists:empleados,id',
            'sub_total'         => 'required|numeric|min:0',
            'detalles'          => 'required|array|min:1',
            // 2. VALIDACIÓN DE CADA LÍNEA DE DETALLE
            'detalles.*.producto_id'      => 'required|exists:productos,id',
            'detalles.*.cantidad'         => 'required|integer|min:1',
            'detalles.*.precio_unitario'  => 'required|numeric|min:0',
        ]);

        // ⭐ REMOVIDO: Ya no es necesario buscar el estado por nombre ni convertir el ID, 
        // porque la vista ya envía el ID numérico correcto.

        try {
            // 3. Ejecutar dentro de una transacción para asegurar que todo se guarde
            $orden = DB::transaction(function () use ($validatedData) {

                // Paso 1: Crear la orden principal (Asignación Masiva)
                $orden = Orden::create($validatedData);

                // Paso 2: Crear los detalles de la orden
                foreach ($validatedData['detalles'] ?? [] as $detalle) {

                    // Calculamos el monto total de la línea
                    $monto = $detalle['cantidad'] * $detalle['precio_unitario'];

                    // Guardar cada detalle
                    $orden->detalles()->create([
                        'producto_id'      => $detalle['producto_id'],
                        'cantidad'         => $detalle['cantidad'],
                        'precio_unitario' => $detalle['precio_unitario'],
                        'monto'            => $monto,
                        // 'orden_id' se llena automáticamente por la relación
                    ]);
                }

                return $orden;
            });

            // 4. Éxito
            return redirect()->route('ordenes.index')->with('success', 'Orden creada exitosamente. Número: ' . $orden->numero_orden);
        } catch (\Exception $e) {
            // 5. Manejo de errores de la base de datos o de la transacción
            return redirect()->back()
                ->withInput()
                ->withErrors(['db_error' => 'Error al guardar la orden: ' . $e->getMessage()])
                ->withInput($request->all());
        }
    }

    /**
     * * * @param Orden $orden
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsServed(Orden $orden)
    {
        $estadoServido = EstadoOrden::whereRaw('LOWER(nombre) = ?', ['servido'])->first();

        if (!$estadoServido) {
            return back()->with('error', 'Error: El estado "Servido" no se encontró en la base de datos de estados de orden.');
        }

        $estadoPagada = EstadoOrden::whereRaw('LOWER(nombre) = ?', ['pagada'])->first();
        $estadoCancelada = EstadoOrden::whereRaw('LOWER(nombre) = ?', ['cancelada'])->first();

        if (
            ($estadoPagada && $orden->estado_orden_id === $estadoPagada->id) ||
            ($estadoCancelada && $orden->estado_orden_id === $estadoCancelada->id)
        ) {
            return back()->with('error', 'La orden ya está ' . $orden->estado->nombre . ' y no puede ser marcada como Servido.');
        }

        DB::beginTransaction();
        try {
            $orden->update(['estado_orden_id' => $estadoServido->id]);

            DB::commit();

            return redirect()->route('ordenes.index')->with('success', "Orden #{$orden->numero_orden} marcada como Servido y lista para la cuenta.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al marcar la orden como Servido: ' . $e->getMessage());
        }
    }

    /**
     * Muestra una orden específica.
     */
    public function show(Orden $orden)
    {
        // Esta línea precarga 'detalles', lo cual está correcto.
        $orden->load(['mesa', 'empleado.persona', 'detalles.producto.item',]);
        return view('ordenes.show', compact('orden'));
    }

    /**
     * Muestra el formulario para editar una orden.
     */
    public function edit(Orden $orden)
    {
        // Aplicar la misma corrección de JOIN si también necesitas la lista de productos en el 'edit'
        $productos = Producto::select('productos.*')
            ->join('items', 'productos.item_id', '=', 'items.id')
            ->orderBy('items.nombre')
            ->get();

        $mesas = Mesa::orderBy('numero')->get();
        $empleados = Empleado::with('persona')->get();
        // ⭐ USAR PLUCK TAMBIÉN EN EDIT PARA CONSISTENCIA
        $estados = EstadoOrden::all()->pluck('nombre', 'id');
        $clientes = Cliente::with('persona')->get();

        return view('ordenes.edit', compact('orden', 'productos', 'mesas', 'empleados', 'estados', 'clientes'));
    }

    /**
     * Actualiza la orden especificada.
     */
    public function update(Request $request, Orden $orden)
    {
        // 1. Validación de datos
        $validatedData = $request->validate([
            'fecha_hora'          => 'required|date',
            'estado_orden_id'     => 'required|exists:estado_ordenes,id',
            'cliente_id'          => 'nullable|exists:clientes,id',
            'empleado_id'         => 'nullable|exists:empleados,id',
            'mesa_id'             => 'required|exists:mesas,id', // Asegurando que se valide mesa_id
            'productos'           => 'required|array|min:1',
            'productos.*.orden_producto_id' => 'nullable|integer', // Para saber qué detalle actualizar/eliminar
            'productos.*.producto_id'       => 'required|exists:productos,id',
            'productos.*.cantidad'          => 'required|integer|min:1',
            'productos.*.precio_unitario'   => 'required|numeric|min:0',
        ]);

        // 2. Calcular el nuevo sub_total con CASTING explícito
        // Usamos (float) para asegurar que PHP interprete los valores como números antes de multiplicar.
        $subTotal = collect($validatedData['productos'])->sum(function ($item) {
            return (float) $item['cantidad'] * (float) $item['precio_unitario'];
        });

        DB::beginTransaction();
        try {
            // 3. Actualizar la orden principal
            $orden->update([
                'fecha_hora'      => $validatedData['fecha_hora'],
                'estado_orden_id' => $validatedData['estado_orden_id'],
                'cliente_id'      => $validatedData['cliente_id'],
                'empleado_id'     => $validatedData['empleado_id'],
                'mesa_id'         => $validatedData['mesa_id'],
                'sub_total'       => $subTotal,
            ]);

            // 4. Sincronizar los detalles de la orden

            // 4.1 Identificar IDs de detalles existentes en la BD
            $currentDetailIds = $orden->ordenProductos->pluck('id')->toArray();
            $submittedDetailIds = collect($validatedData['productos'])
                ->pluck('orden_producto_id')
                ->filter() // Filtra los nulos (nuevos productos)
                ->toArray();

            // 4.2 Eliminar los detalles que fueron quitados del formulario
            $detailsToDelete = array_diff($currentDetailIds, $submittedDetailIds);
            if (!empty($detailsToDelete)) {
                $orden->ordenProductos()->whereIn('id', $detailsToDelete)->delete();
            }

            // 4.3 Insertar/Actualizar los detalles
            foreach ($validatedData['productos'] as $detalle) {

                // --- CORRECCIÓN CLAVE ---
                // Aplicamos (float) al momento de calcular el monto
                $cantidad = (float) $detalle['cantidad'];
                $precio_unitario = (float) $detalle['precio_unitario'];
                $monto = $cantidad * $precio_unitario;

                $data = [
                    'producto_id'       => $detalle['producto_id'],
                    'cantidad'          => $cantidad,
                    'precio_unitario'   => $precio_unitario,
                    'monto'             => $monto,
                ];

                // Si el detalle tiene un ID, lo actualiza. Si no, crea uno nuevo.
                if (!empty($detalle['orden_producto_id'])) {
                    $orden->ordenProductos()->where('id', $detalle['orden_producto_id'])->update($data);
                } else {
                    $orden->ordenProductos()->create($data);
                }
            }

            DB::commit();

            return redirect()->route('ordenes.index')->with('success', 'Orden actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error("Error al actualizar la orden: " . $e->getMessage()); // Siempre es bueno tener un log
            return redirect()->back()
                ->withInput()
                ->withErrors(['db_error' => 'Error al actualizar la orden: ' . $e->getMessage()]);
        }
    }

    /**
     * Elimina la orden.
     */
    public function destroy(Orden $orden)
    {
        try {
            $orden->delete();
            return redirect()->route('ordenes.index')->with('success', 'Orden eliminada.');
        } catch (\Exception $e) {
            return redirect()->route('ordenes.index')->with('error', 'Error al eliminar la orden.');
        }
    }
}
