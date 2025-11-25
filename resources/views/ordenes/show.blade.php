<x-app-layout>
    <!-- Contenedor principal: asume que el layout maneja el fondo (bg-gray-100 dark:bg-gray-900) -->
    <div class="container mx-auto px-4 py-8 max-w-4xl">

        <!-- Header con Título y Botones -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <!-- Título -->
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Orden {{ $orden->numero_orden }}</h1>
                <!-- Fecha -->
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $orden->fecha_hora ? $orden->fecha_hora->format('Y-m-d H:i') : '—' }}
                </p>
            </div>

            <!-- Contenedor de Botones de Acción y Tema -->
            <div class="flex items-center gap-3">
                <!-- Botones de Enlace originales -->
                <a href="{{ route('ordenes.edit', $orden) }}"
                    class="px-3 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition">Editar</a>
                <a href="{{ route('ordenes.index') }}"
                    class="px-3 py-2 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">Volver</a>
            </div>
        </div>

        <!-- Bloque de Detalles Generales -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg mb-6 text-gray-900 dark:text-gray-200 transition-colors duration-300">
            <h2 class="text-xl font-bold mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">Información General</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Cliente</h3>
                    <div class="mt-1 font-medium">{{ $orden->cliente->codigo ?? '—' }}
                        {{ $orden->cliente->persona->nombre ?? '' }}</div>
                </div>

                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Empleado</h3>
                    <div class="mt-1 font-medium">{{ $orden->empleado->persona->nombre ?? '—' }}</div>
                </div>

                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Estado</h3>
                    <div class="mt-1 font-medium">{{ $orden->estado->nombre ?? '—' }}</div>
                </div>

                <div>
                    <h3 class="text-sm text-gray-500 dark:text-gray-400">Subtotal</h3>
                    <div class="mt-1 font-bold text-lg text-gray-900 dark:text-gray-200">{{ number_format($orden->sub_total, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Bloque de Líneas de la Orden (Tabla) -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg text-gray-900 dark:text-gray-200 transition-colors duration-300">
            <div class="flex items-center justify-between mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Líneas</h2>
                
                <div class="flex gap-2">
                    <a href="{{ route('ordenes.createLines', $orden) }}"
                        class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Agregar múltiples líneas</a>
                    <a href="{{ route('orden-productos.index', ['orden_id' => $orden->id]) }}"
                        class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                        Ver líneas de la orden
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">#</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Producto</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Cantidad</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Precio unit.</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Monto</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($orden->detalles as $l)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ $l->id }}</td>
                                {{-- FIX: Se cambió $l->producto->nombre a $l->producto->item->nombre basado en el eager loading del controlador. --}}
                                <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ $l->producto->item->nombre ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ $l->cantidad }}</td>
                                <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ number_format($l->precio_unitario, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ number_format($l->monto, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <a href="{{ route('orden-productos.edit', $l) }}"
                                        class="px-2 text-yellow-600 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-200">Editar</a>
                                    <form action="{{ route('orden-productos.destroy', $l) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('¿Eliminar línea?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-200">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No hay líneas.</td>
                            </tr>
                        @endforelse

                        {{-- Fila para mostrar el subtotal final (opcional, si no está calculado en el loop) --}}
                        <tr class="font-bold">
                             <td colspan="4" class="px-4 py-3 text-sm text-right font-bold text-gray-900 dark:text-gray-100">SUBTOTAL</td>
                            <td class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-gray-100">{{ number_format($orden->sub_total, 2) }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>