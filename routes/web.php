<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpleadosController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\PuntosController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\OrdenProductoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RolesController;
use App\Models\Proveedor;
use App\Http\Controllers\NotaCompraController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotaSalidaController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\ReporteFinancieroController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\TipoController;
use App\Models\NotaVenta;
use App\Http\Controllers\NotaVentaController;
use App\Http\Controllers\ValoracionController;
// ** IMPORTACIÓN NECESARIA PARA EL REPORTE DE PRODUCTOS **
use App\Http\Controllers\ReporteProductoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/dashboard');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // ** RUTAS AÑADIDAS PARA EL REPORTE DE PRODUCTOS POPULARES (CU23 & CU24) **

    // Muestra la vista general del reporte (Histórico)
    Route::get('/reportes/productos-populares', [ReporteProductoController::class, 'index'])
        ->name('reportes.productos.populares.index');

    // Genera el reporte filtrado por mes y año
    Route::post('/reportes/productos-populares', [ReporteProductoController::class, 'generarReporteMensual'])
        ->name('reportes.productos.populares.generar_mensual');

    // -----------------------------------------------------------------------

    Route::resource('valoraciones', ValoracionController::class)->parameters([
        'valoraciones' => 'valoracione',
    ]);

    Route::resource('nota-ventas', NotaVentaController::class);
    Route::resource('tipos', TipoController::class);
    Route::resource('facturas', FacturaController::class);
    Route::get('facturas/{factura}/pdf', [FacturaController::class, 'generarPdf'])->name('facturas.pdf');
    Route::get('nota-ventas/{notaVenta}/pdf', [NotaVentaController::class, 'generarPdf'])->name('nota-ventas.pdf');
    // RUTAS DE REPORTES FINANCIEROS
    Route::get('/reportes/ingresos', [ReporteFinancieroController::class, 'index'])
        ->name('reportes.ingresos.index');

    Route::post('/reportes/ingresos', [ReporteFinancieroController::class, 'calcular'])
        ->name('reportes.ingresos.calcular');

    Route::resource('produccion', ProduccionController::class);
    Route::resource('notas-salida', NotaSalidaController::class);
    Route::resource('items', ItemController::class);
    Route::resource('ingredientes', IngredienteController::class);
    Route::resource('nota-compra', NotaCompraController::class);
    Route::resource('orden-productos', OrdenProductoController::class);

    // RUTAS DE ÓRDENES (CRUCIAL: AGREGANDO LA RUTA DE ACCIÓN)
    // 1. Ruta de acción para marcar como Servida
    Route::post('ordenes/{orden}/served', [OrdenController::class, 'markAsServed'])->name('ordenes.mark_as_served');

    // 2. Rutas para generar Nota de Venta desde la Orden
    Route::get('ordenes/{orden}/generar-venta', [NotaVentaController::class, 'create'])->name('nota_ventas.create_from_orden');
    Route::post('ordenes/{orden}/store-venta', [NotaVentaController::class, 'store'])->name('nota_ventas.store_from_orden');
    Route::get('ordenes/create-lines', [OrdenController::class, 'createLines'])
        ->name('ordenes.createLines');
    // 3. Rutas CRUD de Órdenes
    Route::resource('ordenes', OrdenController::class)
        ->parameters(['ordenes' => 'orden']);


    Route::resource('clientes', ClienteController::class);
    // ⭐ CORRECCIÓN: Quitamos esta línea. Se define debajo con restricción de roles.
    // Route::resource('empleados', EmpleadosController::class); 
    Route::resource('productos', ProductoController::class);
    Route::resource('menus', MenuController::class);
    Route::resource('proveedores', ProveedorController::class)->parameters([
        'proveedores' => 'proveedor',
    ]);
    Route::resource('mesas', MesaController::class);
    Route::resource('roles', RolesController::class)->middleware(['auth']);

    // RUTAS RESTRINGIDAS POR ROL
    Route::group(['middleware' => ['role:Administrador|Cajero|Secretario']], function () {
        Route::resource('usuarios', UsuariosController::class);
        // ⭐ CORRECCIÓN: Ahora Empleados SÓLO se define dentro del grupo.
        Route::resource('empleados', EmpleadosController::class);
    });

    // RUTAS DE DASHBOARD Y BITÁCORA
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');
    Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('/dashboard/fintech', [DashboardController::class, 'fintech'])->name('fintech');

    Route::post('/bitacora/eliminar', [BitacoraController::class, 'eliminar'])->name('bitacora.eliminar');
    Route::get('bitacora/export', [BitacoraController::class, 'export'])->name('bitacora.export');
    Route::resource('bitacora', BitacoraController::class);

    // RUTA DE FALLBACK
    Route::fallback(function () {
        return view('pages/utility/404');
    });
});
