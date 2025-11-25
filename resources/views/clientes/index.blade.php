<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Clientes</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Gestioná clientes y su información personal.</p>
            </div>

            <div class="flex items-center gap-3">
                <form action="{{ route('clientes.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="search" name="q" value="{{ old('q', $q ?? '') }}" placeholder="Buscar por código, nombre, carnet o teléfono..."
                        class="w-64 px-3 py-2 rounded-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500">
                    <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">Buscar</button>
                    <a href="{{ route('clientes.create') }}" class="ml-2 inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Nuevo cliente
                    </a>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 rounded-md bg-green-50 dark:bg-green-900/30 border border-green-100 dark:border-green-800 text-green-800 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Código</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Carnet</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Teléfono</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($clientes as $cliente)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $cliente->codigo }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $cliente->persona->nombre ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $cliente->persona->carnet ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $cliente->persona->telefono ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-right space-x-2">
                                    <a href="{{ route('clientes.show', $cliente) }}" class="px-2 py-1 text-indigo-600 hover:underline">Ver</a>
                                    <a href="{{ route('clientes.edit', $cliente) }}" class="px-2 py-1 text-yellow-600 hover:underline">Editar</a>

                                    <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro querés eliminar este cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 text-red-600 hover:underline">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No hay clientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900/60 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    Mostrando {{ $clientes->firstItem() ?? 0 }} - {{ $clientes->lastItem() ?? 0 }} de {{ $clientes->total() }}
                </div>
                <div>
                    {{ $clientes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>