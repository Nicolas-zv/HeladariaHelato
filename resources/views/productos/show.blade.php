<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">Detalle del Producto: <span class="text-indigo-600 dark:text-indigo-400">{{ $producto->nombre }}</span></h1>

        <div class="bg-white dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-700 p-6 shadow-sm mb-8">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2 text-gray-700 dark:text-gray-300">Información General</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-gray-700 dark:text-gray-300">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre</p>
                    <p class="text-lg font-semibold">{{ $producto->nombre }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Precio</p>
                    <p class="text-lg font-semibold">{{ number_format($producto->precio, 2) }} Bs.</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</p>
                    <p class="text-lg">{{ $producto->tipo->descripcion ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sabor</p>
                    <p class="text-lg">{{ $producto->sabor->descripcion ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menú</p>
                    <p class="text-lg">{{ $producto->menu->cod_menu ?? 'N/A' }}</p>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t dark:border-gray-700">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Descripción</p>
                <p class="text-gray-700 dark:text-gray-300">{{ $producto->descripcion ?? 'Sin descripción.' }}</p>
            </div>
        </div>

        {{-- SECCIÓN DE RECETA --}}
        <div class="bg-white dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2 text-green-700 dark:text-green-400">Receta / Composición</h2>

            @if ($producto->ingredientes->isEmpty())
                <div class="p-4 bg-yellow-100 text-yellow-800 rounded-lg dark:bg-yellow-900 dark:text-yellow-100">
                    Este producto no tiene una receta definida.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ingrediente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cantidad Requerida</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Unidad</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($producto->ingredientes as $ingrediente)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $ingrediente->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 font-mono">
                                        {{ number_format($ingrediente->pivot->cantidad, 3) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $ingrediente->unidad }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('productos.edit', $producto) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition shadow-md flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                Editar Producto
            </a>
            <a href="{{ route('productos.index') }}" class="ml-3 px-4 py-2 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-700 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition">Volver a la Lista</a>
        </div>
    </div>
</x-app-layout>