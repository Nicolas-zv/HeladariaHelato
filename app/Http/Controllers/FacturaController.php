<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\NotaVenta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // ⭐ Importar la Facade de DomPDF

class FacturaController extends Controller
{
    /**
     * Muestra una lista de todas las facturas.
     * Incluye paginación y precarga la nota de venta asociada.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $facturas = Factura::with('notaVenta')->paginate(10);
        return view('facturas.index', compact('facturas'));
    }

    /**
     * Muestra el formulario para crear una nueva factura.
     * Se requiere cargar las notas de venta disponibles para la selección.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $notasVenta = NotaVenta::all(); // Obtener todas las notas de venta
        return view('facturas.create', compact('notasVenta'));
    }

    /**
     * Almacena una nueva factura en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha_hora' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'codigo_control' => 'nullable|string|max:50',
            'nota_venta_id' => 'required|exists:nota_ventas,id|unique:facturas,nota_venta_id',
        ], [
            'nota_venta_id.unique' => 'Ya existe una factura emitida para esta Nota de Venta.',
        ]);

        Factura::create($request->all());

        return redirect()->route('facturas.index')
                            ->with('success', 'Factura creada exitosamente.');
    }

    /**
     * Muestra los detalles de una factura específica.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\View\View
     */
    public function show(Factura $factura)
    {
        // Precarga la nota de venta y, a través de ella, los detalles de la orden
        $factura->load([
            'notaVenta.orden.detalles.producto', // ⭐ Carga de detalles anidados
            'notaVenta.mesa',
            'notaVenta.empleado.persona',
            'notaVenta.cliente.persona',
        ]);
        return view('facturas.show', compact('factura'));
    }

    /**
     * Muestra el formulario para editar una factura existente.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\View\View
     */
    public function edit(Factura $factura)
    {
        $notasVenta = NotaVenta::all();
        return view('facturas.edit', compact('factura', 'notasVenta'));
    }

    /**
     * Actualiza una factura en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Factura $factura)
    {
        $request->validate([
            'fecha_hora' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'codigo_control' => 'nullable|string|max:50',
            // La nota_venta_id es única, pero debe ignorar la factura actual en la validación
            'nota_venta_id' => 'required|exists:nota_ventas,id|unique:facturas,nota_venta_id,' . $factura->id,
        ]);

        $factura->update($request->all());

        return redirect()->route('facturas.index')
                            ->with('success', 'Factura actualizada exitosamente.');
    }

    /**
     * Elimina una factura de la base de datos.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Factura $factura)
    {
        $factura->delete();

        return redirect()->route('facturas.index')
                            ->with('success', 'Factura eliminada correctamente.');
    }
    
    /**
     * Genera y descarga la Factura en formato PDF.
     *
     * @param \App\Models\Factura $factura
     * @return \Illuminate\Http\Response
     */
    public function generarPdf(Factura $factura)
    {
        // 1. Cargar las relaciones necesarias para la Factura (NotaVenta y sus detalles)
        $factura->load([
            'notaVenta.orden.detalles.producto', // Detalles de la orden y sus productos
            'notaVenta.mesa',
            'notaVenta.empleado.persona',
            'notaVenta.cliente.persona',
        ]); 

        // 2. Cargar el logo (Asumiendo que tienes un 'logo.jpg' en /public)
        $logoPath = public_path('logo.jpg');
        $logoBase64 = null;

        if (file_exists($logoPath)) {
            // Convertir la imagen a Base64 para que Dompdf pueda cargarla
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // 3. Pasar los datos a la vista PDF
        $data = [
            'factura' => $factura,
            'notaVenta' => $factura->notaVenta, // Acceso directo para conveniencia en la vista
            'detalles' => $factura->notaVenta->orden->detalles ?? collect(), // Detalles de la orden
            'logoBase64' => $logoBase64,
            'fechaImpresion' => now()->format('d/m/Y H:i:s'),
        ];
        
        // 4. Generar el PDF
        // La vista será resources/views/pdf/factura.blade.php
        $pdf = PDF::loadView('pdf.factura', $data);
        
        // 5. Devolver el PDF para descarga
        $filename = 'Factura-' . $factura->id . '.pdf';

        // Usamos 'download' para forzar la descarga
        return $pdf->download($filename); 
    }
}