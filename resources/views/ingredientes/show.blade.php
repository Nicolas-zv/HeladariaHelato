<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-2xl font-semibold mb-4">Detalle de Ingrediente</h1>

        <div class="bg-white p-6 rounded shadow mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b pb-4 mb-4">
                <div>
                    <div class="text-sm font-medium text-gray-500">Nombre</div>
                    <div class="text-lg font-bold">{{ $ingrediente->nombre }}</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500">Unidad de Medida</div>
                    <div class="text-lg">{{ $ingrediente->unidad }}</div>
                </div>
            </div>

            <div class="text-right">
                <div class="text-sm font-medium text-gray-600 mb-1">Stock Actual</div>
                <div class="text-2xl font-bold text-indigo-600">{{ number_format($ingrediente->stock, 3) }} {{ $ingrediente->unidad }}</div>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('ingredientes.edit', $ingrediente) }}" class="px-4 py-2 bg-yellow-500 text-white rounded">Editar Ingrediente</a>
            <a href="{{ route('ingredientes.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">Volver al Listado</a>
        </div>
    </div>
</x-app-layout>