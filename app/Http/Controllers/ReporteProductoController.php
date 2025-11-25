<?php

namespace App\Http\Controllers;

use App\Models\OrdenProducto;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // CLAVE: Importación de la Facade Log
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

class ReporteProductoController extends Controller
{
    /**
     * Muestra la vista del reporte general del producto más popular (CU23).
     */
    public function index()
    {
        // Obtener el Top 3 de productos más populares de forma general (histórico)
        $productosPopulares = $this->getProductosPopulares(3); // TOP 3 General (CU23)

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        return view('REPORTEPRODUCTOS.productos_populares', [
            'productosPopulares' => $productosPopulares, 
            'reporteMensual' => null, // Inicialmente nulo
            'mes' => null,
            'anio' => null,
            'periodo_formateado' => null, 
            'meses' => $meses, // Para el formulario de filtro
        ]);
    }

    protected function getProductosPopulares(int $limit = 3, Carbon $inicio = null, Carbon $fin = null)
    {
        $ordenProductoTableName = (new OrdenProducto())->getTable();
        $ordenTableName = 'ordenes'; // Asume la tabla de órdenes

        try {
            $query = OrdenProducto::select(
                    'producto_id',
                    // Suma la cantidad vendida
                    DB::raw('SUM(' . $ordenProductoTableName . '.cantidad) as total_vendido'),
                    // Suma el ingreso
                    DB::raw('SUM(' . $ordenProductoTableName . '.monto) as total_ingreso')
                )
                // Carga la relación anidada para obtener el nombre del Item
                ->with(['producto.item']) 
                ->groupBy('producto_id')
                ->orderByDesc('total_vendido');

            if ($inicio && $fin) {
                $query->join($ordenTableName, $ordenProductoTableName . '.orden_id', '=', $ordenTableName . '.id')
                    ->whereBetween($ordenTableName . '.fecha_hora', [$inicio, $fin]);
            }

            $productosPopulares = $query->take($limit)->get();

            return $productosPopulares->map(function ($item) {
                return [
                    'nombre' => $item->producto?->item?->nombre ?? 'Producto Desconocido',
                    'codigo' => $item->producto?->item?->codigo ?? 'N/A',
                    'total_vendido' => (int) $item->total_vendido,
                    'total_ingreso' => number_format($item->total_ingreso, 2, '.', ','),
                ];
            });
        } catch (\Exception $e) {
            // Error logueado correctamente con la Facade Log
            Log::error("Error al obtener el Top {$limit} de productos: " . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Genera el reporte del producto más popular según el mes (CU24).
     */
    public function generarReporteMensual(Request $request)
    {
        $request->validate([
            'mes' => 'required|integer|min:1|max:12',
            'anio' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        $mes = $request->input('mes');
        $anio = $request->input('anio');

        try {
            // Se utiliza 1 como día para crear el objeto Carbon
            $inicio = Carbon::createFromDate($anio, $mes, 1)->startOfDay();
            $fin = Carbon::createFromDate($anio, $mes, 1)->endOfMonth()->endOfDay();
        } catch (InvalidFormatException $e) {
            return redirect()->back()->withErrors(['error' => 'Fecha de reporte inválida.']);
        }
        
        // Obtener el Top 3 dentro del rango (Reporte Mensual - CU24)
        $reporteMensual = $this->getProductosPopulares(3, $inicio, $fin);

        // Obtener el Top 3 general (Histórico - CU23)
        $productosPopulares = $this->getProductosPopulares(3); 

        // Formatear el periodo
        Carbon::setLocale('es'); 
        $periodo_formateado = $inicio->translatedFormat('F') . ' de ' . $anio;
        
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        return view('REPORTEPRODUCTOS.productos_populares', [
            'productosPopulares' => $productosPopulares, 
            'reporteMensual' => $reporteMensual,
            'mes' => $mes,
            'anio' => $anio,
            'periodo_formateado' => $periodo_formateado,
            'meses' => $meses, 
        ])->with('success', "Reporte generado para el mes de " . $periodo_formateado . ".");
    }
}