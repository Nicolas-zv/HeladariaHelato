<x-app-layout>
    {{-- Contenedor principal con fondo para ambos modos --}}
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                            Detalle de Factura #{{ $factura->id }}
                        </h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Información completa de la factura emitida.
                        </p>
                    </div>
                    {{-- Botones de Acción --}}
                    <div class="flex items-center gap-3">
                        <a href="{{ route('facturas.edit', $factura) }}" 
                           class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md transition duration-150">
                            Editar
                        </a>
                        <a href="{{ route('facturas.index') }}" 
                           class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition duration-150">
                            Volver al Listado
                        </a>
                        {{-- Opcional: Agregar botón para descargar PDF si lo implementaste --}}
                        <a href="{{ route('facturas.pdf', $factura) }}" 
                           class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition duration-150">
                            Descargar PDF
                        </a>
                    </div>
                </div>

                {{-- Contenedor de la información de la Factura --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 space-y-4">
                    
                    {{-- Bloque de información general --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 border-b dark:border-gray-700 pb-4">
                        
                        {{-- 1. Nota de Venta Asociada --}}
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nota de Venta Asociada</p>
                            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                <a href="{{ route('nota-ventas.show', $factura->notaVenta->id ?? '#') }}" 
                                   class="hover:underline">
                                    #{{ $factura->notaVenta->id ?? 'N/A' }}
                                </a>
                            </p>
                        </div>
                        
                        {{-- 2. Fecha y Hora de Emisión --}}
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fecha y Hora de Emisión</p>
                            <p class="text-lg text-gray-900 dark:text-gray-100">
                                {{ $factura->fecha_hora->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        
                        {{-- 3. Monto Total --}}
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Monto Total</p>
                            <p class="text-2xl font-extrabold text-green-700 dark:text-green-400">
                                ${{ number_format($factura->monto, 2) }}
                            </p>
                        </div>
                        
                        {{-- 4. Código de Control --}}
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Código de Control</p>
                            <p class="text-lg text-gray-900 dark:text-gray-100">
                                {{ $factura->codigo_control ?? 'No especificado' }}
                            </p>
                        </div>
                    </div>

                    {{-- Bloque de Tiempos --}}
                    <div class="pt-4 border-t dark:border-gray-700">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-2">Información de Auditoría</p>
                        <div class="flex space-x-6 text-sm text-gray-600 dark:text-gray-400 flex-wrap">
                            <p>
                                <strong>Creada:</strong> 
                                {{ $factura->created_at->format('d/m/Y H:i') }} ({{ $factura->created_at->diffForHumans() }})
                            </p>
                            <p>
                                <strong>Última Modificación:</strong> 
                                {{ $factura->updated_at->format('d/m/Y H:i') }} ({{ $factura->updated_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>