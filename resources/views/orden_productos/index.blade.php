<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold">Líneas de Órdenes</h1>
                <p class="text-sm text-gray-600">Listado de líneas (orden_producto)</p>
            </div>

            <div class="flex items-center gap-3">
                @if(!empty($ordenId))
                    <a href="{{ route('orden-productos.create', ['orden_id' => $ordenId]) }}" class="px-4 py-2 bg-green-600 text-white rounded">Agregar línea a orden #{{ $ordenId }}</a>
                    <a href="{{ route('ordenes.show', $ordenId) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded">Volver a orden</a>
                @else
                    <a href="{{ route('orden-productos.create') }}" class="px-4 py-2 bg-green-600 text-white rounded">Nueva línea</a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium">ID</th>
                            <th class="px-4 py-2 text-left text-xs font-medium">Orden</th>
                            <th class="px-4 py-2 text-left text-xs font-medium">Producto</th>
                            <th class="px-4 py-2 text-left text-xs font-medium">Cantidad</th>
                            <th class="px-4 py-2 text-left text-xs font-medium">Precio unit.</th>
                            <th class="px-4 py-2 text-left text-xs font-medium">Monto</th>
                            <th class="px-4 py-2 text-right text-xs font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @forelse($lineas as $l)
                            <tr>
                                <td class="px-4 py-3 text-sm">{{ $l->id }}</td>
                                <td class="px-4 py-3 text-sm">#{{ $l->orden_id }} - {{ $l->orden->numero_orden ?? '' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $l->producto->nombre ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $l->cantidad }}</td>
                                <td class="px-4 py-3 text-sm">{{ number_format($l->precio_unitario, 2) }}</td>
                                <td class="px-4 py-3 text-sm">{{ number_format($l->monto, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <a href="{{ route('orden-productos.edit', $l) }}" class="px-2 text-yellow-600">Editar</a>
                                    <a href="{{ route('orden-productos.show', $l) }}" class="px-2 text-indigo-600">Ver</a>
                                    <form action="{{ route('orden-productos.destroy', $l) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar línea?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 text-red-600">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No hay líneas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 flex items-center justify-between">
                <div class="text-sm text-gray-600">Mostrando {{ $lineas->firstItem() ?? 0 }} - {{ $lineas->lastItem() ?? 0 }} de {{ $lineas->total() }}</div>
                <div>{{ $lineas->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
