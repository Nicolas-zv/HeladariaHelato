<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">
            Detalle de Nota de Venta #{{ $notaVenta->id }}
        </h1>

        {{-- Mensajes de Notificación --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl">

            {{-- Sección de Acciones --}}
            <div class="flex justify-end mb-4 space-x-2 border-b pb-3 dark:border-gray-700">
                {{-- Rutas corregidas a 'nota-ventas' con guion --}}
                <a href="{{ route('nota-ventas.edit', $notaVenta->id) }}"
                    class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition">
                    Editar Nota
                </a>
                <form action="{{ route('nota-ventas.destroy', $notaVenta->id) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
                        onclick="return confirm('¿CONFIRMA ANULAR la Nota de Venta #{{ $notaVenta->id }} y la Orden asociada?')">
                        Anular Nota
                    </button>
                </form>
                <a href="{{ route('nota-ventas.pdf', $notaVenta) }}"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md font-semibold hover:bg-red-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Descargar PDF
                </a>
                <a href="{{ route('nota-ventas.index') }}"
                    class="px-4 py-2 border text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Volver al Listado
                </a>
            </div>

            {{-- Sección de Cabecera y Relaciones --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                {{-- Columna 1: Información de la Nota --}}
                <div>
                    <h2
                        class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2 border-b dark:border-gray-700">
                        📋 Nota de Venta</h2>
                    <p class="text-sm"><strong>ID:</strong> {{ $notaVenta->id }}</p>
                    <p class="text-sm"><strong>Fecha/Hora:</strong> {{ $notaVenta->fecha_hora->format('Y-m-d H:i:s') }}
                    </p>
                    <p class="text-sm"><strong>Tipo de Pago:</strong> {{ ucfirst($notaVenta->tipo_pago ?? 'N/A') }}</p>
                    <p class="text-sm">
                        <strong>Estado de Pago:</strong>
                        <span class="font-bold {{ $notaVenta->pagado ? 'text-green-600' : 'text-red-600' }}">
                            {{ $notaVenta->pagado ? 'PAGADA' : 'PENDIENTE' }}
                        </span>
                    </p>
                </div>

                {{-- Columna 2: Información de la Orden y Personal --}}
                <div>
                    <h2
                        class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2 border-b dark:border-gray-700">
                        👨‍💼 Datos de Servicio</h2>
                    {{-- USO DEL OPERADOR NULL-SAFE (?->) EN TODAS LAS RELACIONES --}}
                    <p class="text-sm"><strong>Mesa:</strong> {{ $notaVenta->mesa?->numero ?? 'N/A' }}</p>
                    <p class="text-sm"><strong>Empleado:</strong>
                        {{ $notaVenta->empleado?->persona?->nombre ?? 'Sistema' }}</p>
                    <p class="text-sm">
                        <strong>Orden ID:</strong>
                        <span class="text-indigo-600 hover:underline cursor-pointer">
                            {{-- Si $notaVenta->orden es null, se muestra N/A --}}
                            {{ $notaVenta->orden?->numero_orden ?? 'N/A (Orden no asociada)' }}
                        </span>
                    </p>
                </div>

                {{-- Columna 3: Cliente y Factura --}}
                <div>
                    <h2
                        class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-4 mb-2 border-b dark:border-gray-700">
                        👤 Cliente</h2>
                    <p class="text-sm"><strong>Cliente:</strong>
                        {{ $notaVenta->cliente?->persona?->nombre ?? 'Consumidor Final' }}</p>

                    <h2
                        class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-4 mb-2 border-b dark:border-gray-700">
                        🧾 Factura Asociada</h2>
                    @if ($notaVenta->facturas->count() > 0)
                        @php $factura = $notaVenta->facturas->first(); @endphp
                        <p class="text-sm"><strong>ID Factura:</strong> {{ $factura->id }}</p>
                        <p class="text-sm"><strong>Monto Facturado:</strong> ${{ number_format($factura->monto, 2) }}
                        </p>
                    @else
                        <p class="text-sm text-gray-500">No se ha generado factura electrónica.</p>
                    @endif
                </div>
            </div>

            {{-- Detalles de la Venta (Ítems de la Orden) --}}
            <h2
                class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-6 mb-3 border-t pt-3 dark:border-gray-700">
                Detalles de la Venta (Productos)</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Producto</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Cantidad</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Precio Unitario</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @php $totalOrden = 0; @endphp

                        {{-- FIX ESTRICTO: Solo entra en el bucle si la orden existe Y tiene detalles --}}
                        @if ($notaVenta->orden && $notaVenta->orden->detalles)
                            @forelse ($notaVenta->orden->detalles as $detalle)
                                @php
                                    $subtotal = $detalle->cantidad * $detalle->precio_unitario;
                                    $totalOrden += $subtotal;
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $detalle->producto?->nombre ?? 'Producto Eliminado' }}
                                        ({{ $detalle->producto?->codigo ?? 'N/A' }})
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $detalle->cantidad }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        ${{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                        ${{ number_format($subtotal, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">La
                                        orden asociada no contiene ítems.</td>
                                </tr>
                            @endforelse
                        @else
                            <tr>
                                <td colspan="4"
                                    class="px-6 py-4 text-center text-red-500 dark:text-red-400 font-semibold">
                                    ⚠️ No se encontró la Orden asociada o no tiene detalles.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"
                                class="px-6 py-4 text-right text-lg font-bold text-gray-900 dark:text-gray-100">TOTAL:
                            </td>
                            <td class="px-6 py-4 text-right text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                ${{ number_format($notaVenta->total, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Nota sobre la discrepancia de totales --}}
            @if (abs($notaVenta->total - $totalOrden) > 0.01)
                <div class="mt-4 p-3 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md text-sm">
                    ⚠️ **Advertencia:** Existe una pequeña diferencia de
                    ${{ number_format(abs($notaVenta->total - $totalOrden), 2) }} entre el total registrado en la
                    Nota de Venta y la suma de los detalles de la Orden. Esto puede deberse a redondeos o ajustes
                    manuales.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
