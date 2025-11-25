<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $menu->cod_menu }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $menu->descripcion ?? '— sin descripción —' }}</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('menus.edit', $menu) }}" class="px-3 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">Editar</a>
                <a href="{{ route('menus.index') }}" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md">Volver</a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md p-6 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-300">Día</h3>
                    <div class="mt-1 text-gray-900 dark:text-gray-100">{{ $menu->dia ?? '—' }}</div>
                </div>

                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-300">Precio</h3>
                    <div class="mt-1 text-gray-900 dark:text-gray-100">{{ number_format($menu->precio, 2) }}</div>
                </div>
            </div>

            <h3 class="text-sm text-gray-500 dark:text-gray-300 mb-2">Productos del menú</h3>
            <div class="space-y-2">
                @if($menu->productos->isEmpty())
                    <div class="text-gray-500 dark:text-gray-400">No hay productos asociados a este menú.</div>
                @else
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($menu->productos as $producto)
                            <li class="py-3 flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $producto->nombre }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($producto->descripcion, 80) }}</div>
                                </div>

                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($producto->precio, 2) }}</div>
                                    <div class="mt-2 space-x-2">
                                        <a href="{{ route('productos.show', $producto) }}" class="px-2 py-1 text-indigo-600 hover:underline">Ver</a>
                                        <a href="{{ route('productos.edit', $producto) }}" class="px-2 py-1 text-yellow-600 hover:underline">Editar</a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>