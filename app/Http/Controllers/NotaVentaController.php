<?php

namespace App\Http\Controllers;

use App\Models\NotaVenta;
use App\Models\Orden;
use App\Models\Cliente;
use App\Models\Mesa;
use App\Models\Empleado;
use App\Models\EstadoOrden; // <-- ¡Nuevo Import!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection; // Necesario para la colección vacía
use Barryvdh\DomPDF\Facade\Pdf; 

class NotaVentaController extends Controller
{
    /**
     * Muestra una lista de las Notas de Venta (INDEX)
     * También lista las Órdenes pendientes de facturar.
     */
    public function index()
    {
        // 1. Obtener Notas de Venta ya generadas
        $notasVenta = NotaVenta::with('cliente.persona', 'empleado.persona', 'mesa', 'facturas')
            ->orderBy('fecha_hora', 'desc')
            ->paginate(15);
            
        // 2. Obtener IDs de Órdenes que YA tienen una NotaVenta asociada (NotaVenta usa el ID de la Orden)
        $ordenesFacturadasIds = NotaVenta::pluck('id');

        // 3. Obtener el ID del estado que indica que está lista para cobrar.
        // Búsqueda robusta case-insensitive para 'Servido' (y fallbacks por si acaso)
        // La condición más probable es 'Servido' según el listado del usuario.
        $estadoListoParaFacturar = EstadoOrden::whereRaw('LOWER(nombre) = ?', ['servido'])
                                        ->orWhereRaw('LOWER(nombre) = ?', ['servida'])
                                        ->orWhereRaw('LOWER(nombre) = ?', ['completada'])
                                        ->first();
        
        $estadoCompletadaId = $estadoListoParaFacturar ? $estadoListoParaFacturar->id : null; 

        // 4. Obtener Órdenes pendientes de facturar
        if (is_null($estadoCompletadaId)) {
             // Si no se encuentra el estado, devolvemos una colección vacía
             // CONSULTE: Verifique si en la tabla 'estado_ordenes' existe el nombre 'Servido' exactamente.
             $ordenesPendientes = collect(); 
        } else {
             $ordenesPendientes = Orden::whereNotIn('id', $ordenesFacturadasIds)
                // Filtro para solo mostrar órdenes que han sido finalizadas/servidas
                ->where('estado_orden_id', $estadoCompletadaId) 
                ->with('mesa', 'cliente.persona')
                ->orderBy('fecha_hora', 'desc')
                ->get();
        }

        return view('nota_ventas.index', compact('notasVenta', 'ordenesPendientes'));
    }

    /**
     * Muestra el formulario para convertir una ORDEN a NOTA DE VENTA (CREATE)
     */
    public function create(Orden $orden)
    {
        // La carga anidada usa 'producto' (relación correcta)
        $orden->load('detalles.producto', 'mesa', 'cliente', 'empleado');
        
        if (NotaVenta::find($orden->id)) {
            return redirect()->route('nota-ventas.show', $orden->id)
                             ->with('warning', 'Esta orden ya fue convertida a Nota de Venta.');
        }

        $clientes = Cliente::all(); 
        $empleados = Empleado::all();

        return view('nota_ventas.create', compact('orden', 'clientes', 'empleados'));
    }

    /**
     * Almacena una nueva Nota de Venta a partir de una Orden (STORE)
     */
    public function store(Request $request, Orden $orden)
    {
        // Validación básica
        $request->validate([
            'tipo_pago' => 'required|string|in:efectivo,tarjeta,transferencia,otro',
            'cliente_id' => 'nullable|exists:clientes,id',
        ]);
        
        // 1. Verificación de existencia previa de Nota de Venta
        if (NotaVenta::find($orden->id)) {
             return back()->with('error', 'Esta orden ya tiene una Nota de Venta asociada.');
        }

        DB::beginTransaction();
        try {
            // 2. Calcular el total a pagar:
            $totalCalculado = $orden->sub_total; 
            
            if ($totalCalculado <= 0) {
                 // Si sub_total no está calculado, lo recalculamos desde los detalles
                 $totalCalculado = $orden->detalles()->sum(DB::raw('cantidad * precio_unitario'));
            }

            // 3. Crear la Nota de Venta
            $notaVenta = NotaVenta::create([
                'id' => $orden->id, 
                'fecha_hora' => now(),
                'total' => $totalCalculado, 
                'pagado' => true, 
                'tipo_pago' => $request->tipo_pago,
                'cliente_id' => $request->cliente_id ?? $orden->cliente_id,
                'empleado_id' => $orden->empleado_id, 
                'mesa_id' => $orden->mesa_id, 
            ]);

            // 4. Actualizar el estado de la Mesa
            if ($orden->mesa_id && $orden->mesa) {
                $orden->mesa->update(['estado' => 'disponible']);
            }
            
            // 5. Opcional: Actualizar el estado de la Orden a 'Pagada/Cerrada' si tienes ese estado.
            // $estadoFacturada = EstadoOrden::where('nombre', 'Pagada/Cerrada')->first();
            // if ($estadoFacturada) {
            //     $orden->update(['estado_orden_id' => $estadoFacturada->id]);
            // }

            DB::commit();

            return redirect()->route('nota_ventas.show', $notaVenta->id)->with('success', 'Nota de Venta generada y pagada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al generar la Nota de Venta: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la nota de venta y sus detalles (SHOW)
     */
    public function show(NotaVenta $notaVenta)
    {
       $notaVenta->load([
        'orden.detalles.producto', // Importante: Cargar detalles y sus productos
        'mesa', 
        'empleado.persona', 
        'cliente.persona', 
        'facturas'
    ]); 

    return view('nota_ventas.show', compact('notaVenta'));
    }

    /**
     * Muestra el formulario para editar (UPDATE: principalmente pago) (EDIT)
     */
    public function edit(NotaVenta $notaVenta)
    {
        $clientes = Cliente::all();
        $empleados = Empleado::all();
        $mesas = Mesa::all();

        return view('nota_ventas.edit', compact('notaVenta', 'clientes', 'empleados', 'mesas'));
    }

    /**
     * Actualiza la nota de venta (solo cabecera) (UPDATE)
     */
    public function update(Request $request, NotaVenta $notaVenta)
    {
        $request->validate([
            'pagado' => 'boolean',
            'tipo_pago' => 'nullable|string|in:efectivo,tarjeta,transferencia,otro',
            'cliente_id' => 'nullable|exists:clientes,id',
            'empleado_id' => 'nullable|exists:empleados,id',
            'mesa_id' => 'nullable|exists:mesas,id',
        ]);
        
        $notaVenta->update($request->only([
            'pagado', 'tipo_pago', 'cliente_id', 'empleado_id', 'mesa_id', 'fecha_hora'
        ]));

        return redirect()->route('nota_ventas.show', $notaVenta)->with('success', 'Nota de Venta actualizada con éxito.');
    }

    /**
     * Elimina la nota de venta (DESTROY)
     */
    public function destroy(NotaVenta $notaVenta)
    {
        DB::beginTransaction();
        try {
            // Revertir el estado de la mesa a 'disponible' (asumiendo que fue 'ocupada' por la orden)
            if ($notaVenta->mesa_id && $notaVenta->mesa) {
                $notaVenta->mesa->update(['estado' => 'disponible']);
            }
            
            // Revertir el estado de la Orden a 'Pendiente' si es necesario
            if ($notaVenta->orden) {
                // ⭐ Opción B implementada: Cambias el estado de la Orden a 'Pendiente' para poder volver a facturarla
                // Búsqueda case-insensitive para 'Pendiente'
                $estadoPendiente = EstadoOrden::whereRaw('LOWER(nombre) = ?', ['pendiente'])->first();
                if ($estadoPendiente) {
                    $notaVenta->orden->update(['estado_orden_id' => $estadoPendiente->id]);
                }
            }
            
            $notaVenta->delete(); 
            
            DB::commit();

            return redirect()->route('nota_ventas.index')->with('success', 'Nota de Venta eliminada con éxito. La Orden asociada ha sido marcada como "Pendiente" para una posible refacturación.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al anular la Nota de Venta: ' . $e->getMessage());
        }
    }
    public function generarPdf(NotaVenta $notaVenta)
    {
        // 1. Cargar las relaciones necesarias
        $notaVenta->load([
            'orden.detalles.producto', // Detalles de la orden y sus productos
            'mesa', 
            'empleado.persona', 
            'cliente.persona', 
            'facturas'
        ]); 

        // 2. Cargar el logo
        $logoPath = public_path('logo.jpg');
        $logoBase64 = null;

        if (file_exists($logoPath)) {
            // Convertir la imagen a Base64 para que Dompdf pueda cargarla sin problemas de ruta.
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // 3. Pasar los datos a la vista PDF
        $data = [
            'notaVenta' => $notaVenta,
            'logoBase64' => $logoBase64,
            'fechaImpresion' => now()->format('d/m/Y H:i:s'),
        ];
        
        // 4. Generar el PDF
        $pdf = PDF::loadView('pdf.nota_venta_recibo', $data);
        
        // 5. Devolver el PDF para descarga o visualización
        $filename = 'NotaVenta-' . $notaVenta->id . '.pdf';

        // Usamos 'download' para forzar la descarga, si quieres verlo en el navegador usa 'stream'
        return $pdf->download($filename); 
    }
}