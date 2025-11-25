<x-app-layout>
    {{-- Contenedor principal con fondo para ambos modos --}}
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen p-4">
        <div class="container mx-auto px-4 py-8 max-w-4xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                        Editar Orden #{{ $orden->numero_orden }}
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Actualiza la información de la orden y sus productos asociados.
                    </p>
                </div>
                <a href="{{ route('ordenes.index') }}"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition duration-150">
                    Volver al Listado
                </a>
            </div>

            {{-- Formulario de Edición de Orden --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('ordenes.update', $orden) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Fecha y Hora --}}
                        <div>
                            <label for="fecha_hora" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Fecha y Hora
                            </label>
                            <input type="datetime-local" name="fecha_hora" id="fecha_hora"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                value="{{ old('fecha_hora', $orden->fecha_hora ? $orden->fecha_hora->format('Y-m-d\TH:i') : '') }}"
                                required>
                            @error('fecha_hora')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Estado de la Orden --}}
                        <div>
                            <label for="estado_orden_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Estado
                            </label>
                            <select name="estado_orden_id" id="estado_orden_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                required>
                                @foreach ($estados as $id => $nombre)
                                    <option value="{{ $id }}"
                                        {{ old('estado_orden_id', $orden->estado_orden_id) == $id ? 'selected' : '' }}>
                                        {{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estado_orden_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cliente --}}
                        <div>
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Cliente
                            </label>
                            <select name="cliente_id" id="cliente_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="">Seleccionar Cliente (Opcional)</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}"
                                        {{ old('cliente_id', $orden->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->persona->nombre ?? 'N/A' }} ({{ $cliente->codigo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Empleado --}}
                        <div>
                            <label for="empleado_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Empleado
                            </label>
                            <select name="empleado_id" id="empleado_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="">Seleccionar Empleado (Opcional)</option>
                                @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id }}"
                                        {{ old('empleado_id', $orden->empleado_id) == $empleado->id ? 'selected' : '' }}>
                                        {{ $empleado->persona->nombre ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('empleado_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Mesa --}}
                        <div>
                            <label for="mesa_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Mesa
                            </label>
                            <select name="mesa_id" id="mesa_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                           dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                required>
                                <option value="">Seleccionar Mesa</option>
                                @foreach ($mesas as $mesa)
                                    <option value="{{ $mesa->id }}"
                                        {{ old('mesa_id', $orden->mesa_id) == $mesa->id ? 'selected' : '' }}>
                                        Mesa #{{ $mesa->numero }} (Capacidad: {{ $mesa->capacidad }})
                                    </option>
                                @endforeach
                            </select>
                            @error('mesa_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Sección de Productos (Líneas de la Orden) --}}
                    <div class="mt-8">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Productos de la Orden
                        </h2>
                        <div id="productos-container" class="space-y-4">
                            @forelse(old('productos', $orden->detalles ?? []) as $index => $detalle)
                                <div
                                    class="grid grid-cols-1 md:grid-cols-5 gap-4 item-row p-4 border dark:border-gray-700 rounded-md relative">
                                    <input type="hidden" name="productos[{{ $index }}][orden_producto_id]"
                                        value="{{ $detalle->id ?? '' }}">

                                    {{-- Producto --}}
                                    <div class="md:col-span-2">
                                        <label for="producto_id_{{ $index }}"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Producto</label>
                                        <select name="productos[{{ $index }}][producto_id]"
                                            id="producto_id_{{ $index }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                                       dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                            required onchange="updatePrecioUnitario(this, {{ $index }})">
                                            <option value="">Seleccionar Producto</option>
                                            @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}"
                                                    data-precio="{{ $producto->precio_venta }}"
                                                    {{ old("productos.$index.producto_id", $detalle->producto_id ?? '') == $producto->id ? 'selected' : '' }}>
                                                    {{ $producto->item->nombre ?? 'N/A' }}
                                                    ({{ number_format($producto->precio_venta, 2) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error("productos.$index.producto_id")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Cantidad --}}
                                    <div>
                                        <label for="cantidad_{{ $index }}"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad</label>
                                        <input type="number" name="productos[{{ $index }}][cantidad]"
                                            id="cantidad_{{ $index }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                                   dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                            value="{{ old("productos.$index.cantidad", $detalle->cantidad ?? 1) }}"
                                            min="1" required oninput="calculateMonto({{ $index }})">
                                        @error("productos.$index.cantidad")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Precio Unitario --}}
                                    <div>
                                        <label for="precio_unitario_{{ $index }}"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Precio
                                            Unitario</label>
                                        <input type="number" step="0.01"
                                            name="productos[{{ $index }}][precio_unitario]"
                                            id="precio_unitario_{{ $index }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                                   dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                            value="{{ old("productos.$index.precio_unitario", $detalle->precio_unitario ?? 0.0) }}"
                                            min="0" required oninput="calculateMonto({{ $index }})">
                                        @error("productos.$index.precio_unitario")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Monto --}}
                                    <div>
                                        <label for="monto_{{ $index }}"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto</label>
                                        <input type="text" name="productos[{{ $index }}][monto]"
                                            id="monto_{{ $index }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 cursor-not-allowed"
                                            value="{{ number_format(old("productos.$index.monto", ($detalle->cantidad ?? 0) * ($detalle->precio_unitario ?? 0)), 2) }}"
                                            readonly>
                                    </div>

                                    {{-- Botón Eliminar Línea --}}
                                    @if ($index > 0 || count(old('productos', $orden->detalles ?? [])) > 1)
                                        {{-- Permitir eliminar si hay más de una línea --}}
                                        <button type="button" onclick="removeItem(this)"
                                            class="absolute top-2 right-2 p-1 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 11-2 0v6a1 1 0 112 0V8z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @empty
                                <div
                                    class="grid grid-cols-1 md:grid-cols-5 gap-4 item-row p-4 border dark:border-gray-700 rounded-md relative">
                                    <input type="hidden" name="productos[0][orden_producto_id]" value="">

                                    {{-- Producto --}}
                                    <div class="md:col-span-2">
                                        <label for="producto_id_0"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Producto</label>
                                        <select name="productos[0][producto_id]" id="producto_id_0"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                                       dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                            required onchange="updatePrecioUnitario(this, 0)">
                                            <option value="">Seleccionar Producto</option>
                                            @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}"
                                                    data-precio="{{ $producto->precio_venta }}">
                                                    {{ $producto->item->nombre ?? 'N/A' }}
                                                    ({{ number_format($producto->precio_venta, 2) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('productos.0.producto_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Cantidad --}}
                                    <div>
                                        <label for="cantidad_0"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad</label>
                                        <input type="number" name="productos[0][cantidad]" id="cantidad_0"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                                   dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                            value="1" min="1" required oninput="calculateMonto(0)">
                                        @error('productos.0.cantidad')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Precio Unitario --}}
                                    <div>
                                        <label for="precio_unitario_0"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Precio
                                            Unitario</label>
                                        <input type="number" step="0.01" name="productos[0][precio_unitario]"
                                            id="precio_unitario_0"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                                   dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                            value="0.00" min="0" required oninput="calculateMonto(0)">
                                        @error('productos.0.precio_unitario')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Monto --}}
                                    <div>
                                        <label for="monto_0"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto</label>
                                        <input type="text" name="productos[0][monto]" id="monto_0"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 cursor-not-allowed"
                                            value="0.00" readonly>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <button type="button" onclick="addItem()"
                            class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-150">
                            Agregar Producto
                        </button>
                    </div>

                    {{-- Errores de la base de datos --}}
                    @if ($errors->has('db_error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Error:</strong>
                            <span class="block sm:inline">{{ $errors->first('db_error') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition duration-150">
                            Actualizar Orden
                        </button>
                        <a href="{{ route('ordenes.show', $orden) }}"
                            class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md transition duration-150">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let itemIndex =
                {{ collect(old('productos', $orden->detalles ?? []))->keys()->max() +
                    1 ??
                    1 }};
            document.addEventListener('DOMContentLoaded', function() {
                // Calcular montos iniciales y subtotal
                document.querySelectorAll('.item-row').forEach((row, index) => {
                    calculateMonto(index);
                });
                calculateSubtotal();
            });

            function addItem() {
                const container = document.getElementById('productos-container');
                const template = `
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 item-row p-4 border dark:border-gray-700 rounded-md relative">
                        <input type="hidden" name="productos[${itemIndex}][orden_producto_id]" value="">
                        
                        <div class="md:col-span-2">
                            <label for="producto_id_${itemIndex}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Producto</label>
                            <select name="productos[${itemIndex}][producto_id]" id="producto_id_${itemIndex}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                           dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" required
                                    onchange="updatePrecioUnitario(this, ${itemIndex})">
                                <option value="">Seleccionar Producto</option>
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}"
                                        data-precio="{{ $producto->precio_venta }}">
                                        {{ $producto->item->nombre ?? 'N/A' }} ({{ number_format($producto->precio_venta, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="cantidad_${itemIndex}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad</label>
                            <input type="number" name="productos[${itemIndex}][cantidad]" id="cantidad_${itemIndex}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                value="1" min="1" required
                                oninput="calculateMonto(${itemIndex})">
                        </div>

                        <div>
                            <label for="precio_unitario_${itemIndex}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Precio Unitario</label>
                            <input type="number" step="0.01" name="productos[${itemIndex}][precio_unitario]" id="precio_unitario_${itemIndex}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                value="0.00" min="0" required
                                oninput="calculateMonto(${itemIndex})">
                        </div>

                        <div>
                            <label for="monto_${itemIndex}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto</label>
                            <input type="text" name="productos[${itemIndex}][monto]" id="monto_${itemIndex}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 cursor-not-allowed"
                                value="0.00" readonly>
                        </div>

                        <button type="button" onclick="removeItem(this)"
                                class="absolute top-2 right-2 p-1 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 11-2 0v6a1 1 0 112 0V8z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', template);
                itemIndex++;
                calculateSubtotal();
            }

            function removeItem(button) {
                const row = button.closest('.item-row');
                row.remove();
                calculateSubtotal();
            }

            function updatePrecioUnitario(selectElement, index) {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const precio = selectedOption.dataset.precio || '0.00';
                document.getElementById(`precio_unitario_${index}`).value = parseFloat(precio).toFixed(2);
                calculateMonto(index);
            }

            function calculateMonto(index) {
                const cantidad = parseFloat(document.getElementById(`cantidad_${index}`).value) || 0;
                const precioUnitario = parseFloat(document.getElementById(`precio_unitario_${index}`).value) || 0;
                const monto = cantidad * precioUnitario;
                document.getElementById(`monto_${index}`).value = monto.toFixed(2);
                calculateSubtotal();
            }

            function calculateSubtotal() {
                let total = 0;
                document.querySelectorAll('[id^="monto_"]').forEach(input => {
                    total += parseFloat(input.value) || 0;
                });
                // Actualizar el campo oculto del sub_total si existe en la vista principal,
                // o simplemente enviar los detalles y que el controlador lo calcule.
                // Por ahora, solo calculamos el total de las líneas para el JS.
                // Si tienes un input de sub_total visible, actualízalo aquí.
            }
        </script>
    @endpush
</x-app-layout>
