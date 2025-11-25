<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold">Estados de Orden</h1>
                <p class="text-sm text-gray-600">Gestión de estados que pueden tener las órdenes.</p>
            </div>

            <div class="flex items-center gap-3">
                <form action="{{ route('estado_ordenes.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="search" name="q" value="{{ old('q', $q ?? '') }}" placeholder="Buscar por nombre..."
                        class="w-56 px-3 py-2 rounded border bg-white text-sm">
                    <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded">Buscar</button>
                </form>

                <a href="{{ route('estado_ordenes.create') }}" class="ml-2 px-4 py-2 bg-green-600 text-white rounded">Nuevo estado</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-800 rounded">{{ $errors->first() }}</div>
        @endif

        <div class="bg-white rounded shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium">Nombre</th>
                            <th class="px-4 py-2 text-left text-xs font-medium">Órdenes</th>
                            <th class="px-4 py-2 text-right text-xs font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @forelse($estados as $estado)
                            <tr>
                                <td class="px-4 py-3 text-sm">{{ $estado->nombre }}</td>
                                <td class="px-4 py-3 text-sm">{{ $estado->ordenes_count }}</td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <a href="{{ route('estado_ordenes.show', $estado) }}" class="px-2 text-indigo-600">Ver</a>
                                    <a href="{{ route('estado_ordenes.edit', $estado) }}" class="px-2 text-yellow-600">Editar</a>
                                    <form action="{{ route('estado_ordenes.destroy', $estado) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar estado?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 text-red-600">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500">No hay estados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 flex items-center justify-between">
                <div class="text-sm text-gray-600">Mostrando {{ $estados->firstItem() ?? 0 }} - {{ $estados->lastItem() ?? 0 }} de {{ $estados->total() }}</div>
                <div>{{ $estados->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>