<x-app-layout>
    {{-- Contenedor principal para fondo de página --}}
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    {{-- Título --}}
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Facturas</h1>
                    {{-- Subtítulo --}}
                    <p class="text-sm text-gray-600 dark:text-gray-400">Listado y gestión de facturas emitidas</p>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Formulario de Búsqueda --}}
                    <form action="{{ route('facturas.index') }}" method="GET" class="flex items-center gap-2">
                        <input type="search" name="q" value="{{ old('q', $q ?? '') }}" placeholder="Buscar por código, monto o N° Nota Venta..."
                            class="w-64 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-500 dark:placeholder-gray-400">
                        <button type="submit" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition duration-150">Buscar</button>
                    </form>

                    {{-- Botón de Nueva Factura --}}
                    <a href="{{ route('facturas.create') }}" class="ml-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-150">Nueva Factura</a>
                </div>
            </div>

            {{-- Mensajes de Sesión (Éxito y Error) --}}
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 dark:bg-green-900 text-green-800 dark:text-green-300 rounded border border-green-200 dark:border-green-600">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900 text-red-800 dark:text-red-300 rounded border border-red-200 dark:border-red-600">{{ session('error') }}</div>
            @endif

            {{-- Tarjeta de la Tabla --}}
            <div class="bg-white dark:bg-gray-800 rounded shadow overflow-hidden">
                <div class="overflow-x-auto">
                    {{-- La tabla usa border-gray-200 / dark:border-gray-700 --}}
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        
                        {{-- Cabecera de la Tabla --}}
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nota Venta ID</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha Emisión</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Monto Total</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Código Control</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        
                        {{-- Cuerpo de la Tabla --}}
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($facturas as $factura)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-100">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $factura->id }}</td>
                                    <td class="px-4 py-3 text-sm text-indigo-600 dark:text-indigo-400 font-medium">{{ $factura->notaVenta->id ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $factura->fecha_hora->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-green-700 dark:text-green-400">${{ number_format($factura->monto, 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $factura->codigo_control ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm text-right whitespace-nowrap">
                                        <a href="{{ route('facturas.show', $factura) }}" class="px-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200">Ver</a>
                                        <a href="{{ route('facturas.edit', $factura) }}" class="px-2 text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-200">Editar</a>
                                        <form action="{{ route('facturas.destroy', $factura) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar Factura #{{ $factura->id }}? Esta acción es irreversible.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No hay facturas registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="p-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Mostrando {{ $facturas->firstItem() ?? 0 }} - {{ $facturas->lastItem() ?? 0 }} de {{ $facturas->total() }}</div>
                    <div>{{ $facturas->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>