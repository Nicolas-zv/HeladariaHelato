<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">Registro de Salidas de Inventario</h1>

        <div class="flex justify-end mb-4">
            <a href="{{ route('notas-salida.create') }}" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition shadow-md">
                Registrar Salida
             </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>
        @endif

        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código Nota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Afectado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad Salida</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($salidas as $salida)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $salida->fecha_hora->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $salida->codigo ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $salida->item->nombre ?? 'Item Eliminado' }} ({{ $salida->item->codigo ?? 'N/A' }})</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-bold text-right">- {{ number_format($salida->cantidad, 0) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('notas-salida.destroy', $salida) }}" method="POST" class="inline" onsubmit="return confirm('ATENCIÓN: ¿Desea anular esta Nota de Salida? El stock se revertirá.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Anular</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay Notas de Salida registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $salidas->links() }}</div>
    </div>
</x-app-layout>