<?php

namespace App\Http\Controllers;

use App\Models\NotaVenta;
use App\Models\Produccion;
use Illuminate\Http\Request;
use Carbon\Carbon; // Importar Carbon para manipulación de fechas

class ReporteFinancieroController extends Controller
{
    /**
     * Muestra la vista del reporte financiero con datos por defecto (últimos 30 días).
     * Esta función maneja la ruta GET.
     */
    public function index(Request $request)
    {
        // 1. OBTENER FECHAS POR DEFECTO (Últimos 30 días)
        $fechaInicio = now()->subDays(30)->toDateString();
        $fechaFin = now()->toDateString();
        
        // 2. Calcular resultados para el rango por defecto
        $resultados = $this->obtenerResultados($fechaInicio, $fechaFin);

        // 3. Devolver la vista con los datos iniciales
        return view('reportes.ingresos_totales', compact('fechaInicio', 'fechaFin', 'resultados'));
    }

    /**
     * Procesa la solicitud POST del formulario de cálculo.
     * Esta función maneja la ruta POST.
     */
    public function calcular(Request $request)
    {
        // 1. VALIDAR FECHAS ENVIADAS POR EL FORMULARIO
        $request->validate([
            'inicio' => 'required|date',
            'fin' => 'required|date|after_or_equal:inicio',
        ]);
        
        $fechaInicio = $request->input('inicio');
        $fechaFin = $request->input('fin');
        
        // 2. Calcular resultados para el rango solicitado
        $resultados = $this->obtenerResultados($fechaInicio, $fechaFin);

        // 3. DEVOLVER LA VISTA CON LOS NUEVOS DATOS CALCULADOS
        return view('reportes.ingresos_totales', compact('fechaInicio', 'fechaFin', 'resultados'));
    }

    /**
     * Lógica de cálculo interna para ingresos, costos y utilidad.
     * Es un método auxiliar (protected) para ser reutilizado por 'index' y 'calcular'.
     * @param string $fechaInicio
     * @param string $fechaFin
     * @return object
     */
    protected function obtenerResultados($fechaInicio, $fechaFin)
    {
        // --- LÓGICA DE FECHAS MEJORADA ---

        // $start será el inicio del día ($fechaInicio), inclusive.
        $start = $fechaInicio . ' 00:00:00';

        // $nextDay será el inicio del día siguiente a $fechaFin (exclusivo).
        // Esto captura todas las transacciones hasta el último instante de $fechaFin.
        $nextDay = Carbon::parse($fechaFin)->addDay()->toDateString() . ' 00:00:00';

        $resultados = (object) [
            'ingresosBrutos' => 0,
            'costoTotalProduccion' => 0,
            'utilidadBruta' => 0,
        ];

        // CÁLCULO DE INGRESOS BRUTOS (Suma de todas las ventas)
        $ingresosBrutos = NotaVenta::query()
            ->where('fecha_hora', '>=', $start) // Mayor o igual al inicio del día
            ->where('fecha_hora', '<', $nextDay)  // Estrictamente menor al inicio del día siguiente
            ->sum('total');

        // CÁLCULO DE COSTO TOTAL DE PRODUCCIÓN (Costo de Mercancía Vendida Simplificado)
        $costoTotalProduccion = Produccion::query()
            ->where('fecha_produccion', '>=', $start)
            ->where('fecha_produccion', '<', $nextDay)
            ->sum('costo_total_ingredientes');
        
        // CÁLCULO DE UTILIDAD/GANANCIA BRUTA
        $utilidadBruta = $ingresosBrutos - $costoTotalProduccion;

        // ASIGNAR RESULTADOS
        $resultados->ingresosBrutos = $ingresosBrutos;
        $resultados->costoTotalProduccion = $costoTotalProduccion;
        $resultados->utilidadBruta = $utilidadBruta;

        return $resultados;
    }
}