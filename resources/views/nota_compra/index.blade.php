<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">📝 Gestión de Notas de Compra</h1>

        <div class="flex justify-end mb-4">
            <a href="{{ route('nota-compra.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition shadow-md">
                Nueva Nota de Compra
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md dark:bg-green-800 dark:text-green-100">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md dark:bg-red-800 dark:text-red-100">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha y Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proveedor</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($notasCompra as $nota)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 font-medium">{{ $nota->codigo ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $nota->fecha_hora->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $nota->proveedor->descripcion ?? 'Sin Proveedor' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 text-right font-semibold">${{ number_format($nota->total, 2) }}</td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('nota-compra.show', $nota) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>
                                <a href="{{ route('nota-compra.edit', $nota) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                <form action="{{ route('nota-compra.destroy', $nota) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar esta Nota de Compra? ¡Se revertirá el stock de los Items! ');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No hay Notas de Compra registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $notasCompra->links() }}
        </div>
    </div>
</x-app-layout>