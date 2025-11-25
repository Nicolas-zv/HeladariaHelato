<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">Gestión de Productos Finales</h1>

        <div class="flex justify-end mb-4">
            <a href="{{ route('productos.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition shadow-md">
                Nuevo Producto
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código (Item)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($productos as $producto)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            {{-- Acceso al Item --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $producto->item->codigo ?? 'N/A' }}</td>
                            
                            {{-- Acceso al Producto --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $producto->item->nombre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($producto->precio, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $producto->tipo->descripcion }}</td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('productos.edit', $producto) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar Producto e Item asociado?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay productos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $productos->links() }}</div>
    </div>
</x-app-layout>