<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Detalle de Valoración</h1>
            <a href="{{ route('valoraciones.index') }}" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                ← Volver al Listado
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-sm p-6 lg:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">

                <!-- Código -->
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Código</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $valoracion->codigo }}</p>
                </div>

                <!-- Fecha y Hora -->
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fecha y Hora</p>
                    <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $valoracion->fecha_hora->format('d/m/Y H:i:s') }}</p>
                </div>

                <!-- Cliente -->
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Cliente</p>
                    <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $valoracion->nombre_cliente }}</p>
                </div>

                <!-- ID Nota de Venta -->
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">ID Nota de Venta</p>
                    <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $valoracion->nota_venta_id ?? 'N/A' }}</p>
                </div>

                <!-- Calificación General -->
                <div class="md:col-span-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Calificación General</p>
                    <div class="mt-2 flex items-center gap-3">
                        <span class="text-3xl font-extrabold
                            @if ($valoracion->experiencia_general >= 4)
                                text-green-600 dark:text-green-400
                            @elseif ($valoracion->experiencia_general >= 3)
                                text-yellow-600 dark:text-yellow-400
                            @else
                                text-red-600 dark:text-red-400
                            @endif
                        ">
                            {{ $valoracion->experiencia_general }} / 5
                        </span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            ({{ match($valoracion->experiencia_general) {
                                5 => '¡Excelente Experiencia!',
                                4 => 'Muy Bueno',
                                3 => 'Aceptable',
                                2 => 'Pobre',
                                1 => 'Pésimo',
                                default => 'Sin calificación'
                            } }})
                        </span>
                    </div>
                </div>

                <!-- Comentario -->
                <div class="md:col-span-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Comentario</p>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-md border border-gray-200 dark:border-gray-700 text-sm text-gray-700 dark:text-gray-300 italic">
                        {{ $valoracion->comentario ?? 'No se dejó ningún comentario.' }}
                    </div>
                </div>

            </div>

            <!-- Acciones -->
            <div class="flex justify-end gap-3 pt-6 border-t mt-6 border-gray-100 dark:border-gray-700">
                <a href="{{ route('valoraciones.edit', $valoracion) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                    Editar
                </a>
                <form action="{{ route('valoraciones.destroy', $valoracion) }}" method="POST" onsubmit="return confirm('¿Seguro querés eliminar esta valoración? Esta acción no se puede deshacer.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>