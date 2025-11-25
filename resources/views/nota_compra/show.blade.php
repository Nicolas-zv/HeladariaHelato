<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">📋 Detalle de Nota de Compra: {{ $notaCompra->codigo }}</h1>

        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl">
            {{-- Datos de la Nota de Compra --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 pb-4 border-b dark:border-gray-700">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Código:</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $notaCompra->codigo }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha y Hora:</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $notaCompra->fecha_hora->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Proveedor:</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $notaCompra->proveedor->nombre ?? 'Sin Proveedor' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de la Nota:</p>
                    <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($notaCompra->total, 2) }}</p>
                </div>
            </div>

            {{-- Detalles de las Compras --}}
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Ítems Comprados</h2>
            
            <div class="overflow-x-auto rounded-lg border dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nro Compra</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item (Código)</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Costo Unitario</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($notaCompra->compras as $compra)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $compra->nro_compra }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $compra->item->nombre ?? 'Item Eliminado' }} ({{ $compra->item->codigo ?? 'N/A' }})</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">{{ number_format($compra->cantidad, 0) }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">${{ number_format($compra->costo, 2) }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-200 text-right">${{ number_format($compra->cantidad * $compra->costo, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 pt-4 border-t dark:border-gray-700 flex justify-start space-x-3">
                <a href="{{ route('nota-compra.edit', $notaCompra) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition shadow-md">
                    Editar Nota
                </a>
                <a href="{{ route('nota-compra.index') }}" class="px-4 py-2 text-gray-700 dark:text-gray-300 border rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition">Volver al Listado</a>
            </div>
        </div>
    </div>
</x-app-layout>