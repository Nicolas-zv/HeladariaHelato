<?php

namespace App\Http\Controllers;

use App\Models\NotaCompra;
use App\Models\Proveedor;
use App\Models\Item;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\NotaCompraRequest; // Asumiendo que tienes un Request para validación

class NotaCompraController extends Controller
{
    /**
     * Muestra una lista de las notas de compra. (INDEX)
     */
    public function index()
    {
        $notasCompra = NotaCompra::with('compras.item', 'proveedor')->paginate(10);

        return view('nota_compra.index', compact('notasCompra'));;
    }

    /**
     * Muestra el formulario para crear una nueva nota de compra. (CREATE)
     */
    public function create()
    {
       $proveedores = Proveedor::all();
        $items = Item::all(['id', 'codigo', 'nombre']);
        return view('nota_compra.create', compact('proveedores', 'items'));
    }

    /**
     * Almacena una nueva nota de compra y sus detalles. (STORE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:255|unique:nota_compras,codigo',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'fecha_hora' => 'nullable|date',
            'compras' => 'required|array|min:1',
            'compras.*.item_id' => 'required|exists:items,id',
            'compras.*.cantidad' => 'required|integer|min:1',
            'compras.*.costo' => 'required|numeric|min:0',
            'compras.*.nro_compra' => 'required|string|unique:compras,nro_compra',
        ]);
        DB::beginTransaction();
        try {
            $total = 0;
            $comprasData = $request->input('compras');
            foreach ($comprasData as $compra) {
                $total += ($compra['cantidad'] * $compra['costo']); // Costo unitario * Cantidad
            }
            $notaCompra = NotaCompra::create([
                'codigo' => $request->codigo,
                'fecha_hora' => $request->fecha_hora ?? now(),
                'proveedor_id' => $request->proveedor_id,
                'total' => $total, 
            ]);
            foreach ($comprasData as $compra) {
                $notaCompra->compras()->create([
                    'item_id' => $compra['item_id'],
                    'nro_compra' => $compra['nro_compra'],
                    'cantidad' => $compra['cantidad'],
                    'costo' => $compra['costo'],
                ]);

                $item = Item::find($compra['item_id']);
                $item->increment('cantidad', $compra['cantidad']); // 'cantidad' es el stock en la tabla 'items'
            }

            DB::commit();

            return redirect()->route('nota_compras.show', $notaCompra)->with('success', 'Nota de Compra creada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la Nota de Compra: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la nota de compra y sus detalles. (SHOW)
     */
    public function show(NotaCompra $notaCompra)
    {
        $notaCompra->load('compras.item', 'proveedor');
        return view('nota_compra.show', compact('notaCompra'));
    }

    /**
     * Muestra el formulario para editar la nota de compra. (EDIT)
     */
    public function edit(NotaCompra $notaCompra)
    {
        $proveedores = Proveedor::all();
        $items = Item::all(['id', 'codigo', 'nombre']);
        $notaCompra->load('compras'); // Cambié $notas_compra a $notaCompra
        return view('nota_compra.edit', compact('notaCompra', 'proveedores', 'items'));
    }

    /**
     * Actualiza la nota de compra y sus detalles. (UPDATE)
     */
    public function update(Request $request, NotaCompra $notaCompra)
    {
        $request->validate([
            'codigo' => 'required|string|max:255|unique:nota_compras,codigo,' . $notaCompra->id,
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'fecha_hora' => 'nullable|date',
        ]);
        $notaCompra->update([
            'codigo' => $request->codigo,
            'proveedor_id' => $request->proveedor_id,
            'fecha_hora' => $request->fecha_hora ?? now(),
        ]);

        return redirect()->route('nota_compras.show', $notaCompra)->with('success', 'Nota de Compra actualizada con éxito.');
    }

    /**
     * Elimina la nota de compra y sus detalles. (DESTROY)
     */
    public function destroy(NotaCompra $notaCompra)
    {
        DB::beginTransaction();
        try {
            foreach ($notaCompra->compras as $compra) {
                $item = Item::find($compra->item_id);
                if ($item) {
                    // Decrementar el stock en la cantidad de la compra
                    $item->decrement('cantidad', $compra->cantidad);
                }
            }
            $notaCompra->delete();

            DB::commit();

            return redirect()->route('nota_compras.index')->with('success', 'Nota de Compra y sus detalles eliminados con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la Nota de Compra: ' . $e->getMessage());
        }
    }
}
