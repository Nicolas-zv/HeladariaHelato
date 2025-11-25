<?php

namespace App\Http\Controllers;

use App\Models\OrdenProducto;
use App\Models\Orden;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenProductoController extends Controller
{
    /**
     * Mostrar listado de orden_producto.
     * Opcionalmente se filtra por orden_id con ?orden_id=#
     */
    public function index(Request $request)
    {
        $ordenId = $request->query('orden_id');

        $query = OrdenProducto::with(['producto', 'orden']);

        if ($ordenId) {
            $query->where('orden_id', $ordenId);
        }

        $lineas = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('orden_productos.index', compact('lineas', 'ordenId'));
    }

    /**
     * Formulario para crear nueva línea (orden_producto).
     * Se puede recibir ?orden_id= para preseleccionar la orden.
     */
    public function create(Request $request)
    {
        $ordenId = $request->query('orden_id');
        $orden = $ordenId ? Orden::find($ordenId) : null;
        $productos = Producto::orderBy('nombre')->get();

        return view('orden_productos.create', compact('orden', 'productos'));
    }

    /**
     * Guardar nueva línea.
     * Actualiza subtotal de la orden sumando el monto creado.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'orden_id' => ['required', 'exists:ordenes,id'],
            'producto_id' => ['required', 'exists:productos,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'precio_unitario' => ['nullable', 'numeric', 'min:0'],
        ]);

        $orden = Orden::findOrFail($data['orden_id']);
        $producto = Producto::findOrFail($data['producto_id']);

        $cantidad = (int) $data['cantidad'];
        $precioUnitario = $data['precio_unitario'] ?? $producto->precio;
        $montoLinea = round($precioUnitario * $cantidad, 2);

        // Iniciar transacción manual
        DB::beginTransaction();

        try {
            // Crear siempre una nueva línea (no merge)
            $linea = OrdenProducto::create([
                'orden_id' => $orden->id,
                'producto_id' => $producto->id,
                'cantidad' => $cantidad,
                'precio_unitario' => $precioUnitario,
                'monto' => $montoLinea,
            ]);

            // Sumar monto al subtotal de la orden
            $orden->sub_total = (float)$orden->sub_total + $montoLinea;
            $orden->save();

            DB::commit();

            return redirect()->route('orden-productos.index', ['orden_id' => $orden->id])
                ->with('success', 'Línea agregada correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Ocurrió un error: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar una línea
     */
    public function show(OrdenProducto $ordenProducto)
    {
        $ordenProducto->load(['producto', 'orden']);
        return view('orden_productos.show', ['linea' => $ordenProducto]);
    }

    /**
     * Formulario para editar una línea.
     */
    public function edit(OrdenProducto $ordenProducto)
    {
        $ordenProducto->load('producto', 'orden');
        $productos = Producto::orderBy('nombre')->get();
        return view('orden_productos.edit', compact('ordenProducto', 'productos'));
    }

    /**
     * Actualizar línea.
     * Recalcula y actualiza el subtotal de la orden (resta monto viejo y suma monto nuevo).
     */
    public function update(Request $request, OrdenProducto $ordenProducto)
    {
        $data = $request->validate([
            'producto_id' => ['required', 'exists:productos,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'precio_unitario' => ['nullable', 'numeric', 'min:0'],
        ]);

        $orden = $ordenProducto->orden;
        $producto = Producto::findOrFail($data['producto_id']);

        $precioUnitario = $data['precio_unitario'] ?? $producto->precio;
        $cantidad = (int) $data['cantidad'];
        $montoNuevo = round($precioUnitario * $cantidad, 2);

        // ajustar subtotal: restar monto viejo y sumar monto nuevo
        $orden->sub_total = (float)$orden->sub_total - (float)$ordenProducto->monto + $montoNuevo;

        // actualizar línea
        $ordenProducto->update([
            'producto_id' => $producto->id,
            'cantidad' => $cantidad,
            'precio_unitario' => $precioUnitario,
            'monto' => $montoNuevo,
        ]);

        $orden->save();

        return redirect()->route('orden-productos.index', ['orden_id' => $orden->id])
            ->with('success', 'Línea actualizada correctamente.');
    }

    /**
     * Eliminar línea y ajustar subtotal.
     */
    public function destroy(OrdenProducto $ordenProducto)
    {
        $orden = $ordenProducto->orden;

        // restar monto y eliminar
        $orden->sub_total = (float)$orden->sub_total - (float)$ordenProducto->monto;
        $orden->save();

        $ordenProducto->delete();

        return redirect()->route('orden-productos.index', ['orden_id' => $orden->id])
            ->with('success', 'Línea eliminada correctamente.');
    }
}
