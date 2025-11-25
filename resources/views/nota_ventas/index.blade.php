<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">📝 Gestión de Notas de Venta</h1>
        
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('warning') }}
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- SECCIÓN 1: ÓRDENES PENDIENTES DE FACTURAR --}}
        {{-- ========================================================================= --}}
        <div class="mb-8 p-6 bg-red-50 dark:bg-red-900 rounded-lg shadow-lg border border-red-300 dark:border-red-700">
            <h2 class="text-2xl font-semibold mb-4 text-red-700 dark:text-red-300">🚨 Órdenes Pendientes de Pago / Facturación</h2>
            
            @if ($ordenesPendientes->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">No hay órdenes completadas y pendientes de facturar en este momento.</p>
            @else
                <div class="shadow overflow-hidden border-b border-red-200 dark:border-red-800 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-red-200 dark:divide-red-800">
                        <thead class="bg-red-100 dark:bg-red-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-700 dark:text-red-300 uppercase tracking-wider">Orden #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-700 dark:text-red-300 uppercase tracking-wider">Mesa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-700 dark:text-red-300 uppercase tracking-wider">SubTotal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-700 dark:text-red-300 uppercase tracking-wider">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-red-200 dark:divide-red-800">
                            @foreach ($ordenesPendientes as $orden)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-800 dark:text-red-200">{{ $orden->numero_orden }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $orden->mesa->numero ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">${{ number_format($orden->sub_total, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    {{-- Botón para ir a la vista de creación de NotaVenta --}}
                                    <a href="{{ route('nota_ventas.create_from_orden', $orden->id) }}" class="px-3 py-1 bg-indigo-600 text-white rounded-md text-xs hover:bg-indigo-700 transition">
                                        Generar Nota de Venta
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        
        {{-- ========================================================================= --}}
        {{-- SECCIÓN 2: NOTAS DE VENTA YA GENERADAS --}}
        {{-- ========================================================================= --}}
        <h2 class="text-2xl font-semibold mb-4 mt-8 text-gray-900 dark:text-gray-100">✅ Notas de Venta Generadas</h2>

        <div class="shadow overflow-hidden border-b border-gray-200 dark:border-gray-700 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID Nota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mesa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pago</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($notasVenta as $nota)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $nota->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $nota->fecha_hora->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $nota->mesa->numero ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $nota->cliente->persona->nombre ?? 'Consumidor Final' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">${{ number_format($nota->total, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $nota->pagado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $nota->pagado ? 'Pagada' : 'Pendiente' }} ({{ $nota->tipo_pago ?? 'N/A' }})
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('nota-ventas.show', $nota->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2 dark:text-indigo-400 dark:hover:text-indigo-500">Ver</a>
                            <a href="{{ route('nota-ventas.edit', $nota->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-2 dark:text-yellow-400 dark:hover:text-yellow-500">Editar</a>
                            <form action="{{ route('nota-ventas.destroy', $nota->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-500" onclick="return confirm('¿Seguro que desea anular esta nota de venta y la orden asociada?')">Anular</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $notasVenta->links() }}
        </div>
    </div>
</x-app-layout>