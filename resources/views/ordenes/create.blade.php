<x-app-layout>
    {{-- Contenedor principal con fondo para ambos modos --}}
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="container mx-auto px-4 py-8 max-w-4xl">
            <h1 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Crear Orden</h1>

            {{-- Bloque para mostrar errores de validación o DB --}}
            @if ($errors->any())
                <div class="mb-4 p-4 rounded-md bg-red-100 dark:bg-red-950 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-300 shadow-md">
                    <p class="font-bold">Hay errores en el formulario:</p>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    @if ($errors->has('db_error'))
                        <p class="mt-2 font-mono text-xs dark:text-red-400">**ERROR DB:** {{ $errors->first('db_error') }}</p>
                    @endif
                </div>
            @endif

            {{-- Contenedor del Formulario --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                <form action="{{ route('ordenes.store') }}" method="POST" id="ordenForm">
                    @csrf

                    {{-- BLOQUE PRINCIPAL: Número, Fecha, Mesa y Estado --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        {{-- CAMPO 1: NÚMERO DE ORDEN --}}
                        <div>
                            <label for="numero_orden" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Número de
                                orden</label>
                            <input type="text" id="numero_orden" name="numero_orden"
                                value="{{ old('numero_orden', 'AUTO') }}" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" />
                            @error('numero_orden')
                                <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- CAMPO 2: FECHA / HORA --}}
                        <div>
                            <label for="fecha_hora" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha / Hora</label>
                            <input type="datetime-local" id="fecha_hora" name="fecha_hora"
                                value="{{ old('fecha_hora', now()->format('Y-m-d\TH:i')) }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" />
                            @error('fecha_hora')
                                <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- CAMPO 3: MESA (Visible y Requerido) --}}
                        <div>
                            <label for="mesa_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mesa</label>
                            <select id="mesa_id" name="mesa_id" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">— Seleccionar —</option>
                                @foreach ($mesas as $mesa)
                                    <option value="{{ $mesa->id }}" {{ old('mesa_id') == $mesa->id ? 'selected' : '' }}>
                                        {{ $mesa->numero }}
                                        {{ $mesa->capacidad ? ' (Cap: ' . $mesa->capacidad . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mesa_id')
                                <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- CAMPO 4: ESTADO --}}
                        <div>
                            <label for="estado_orden_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                            <select id="estado_orden_id" name="estado_orden_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">— Seleccionar —</option>

                                @php
                                    // En la vista de creación, solo verificamos el valor anterior (old())
                                    $currentStatusId = old('estado_orden_id');
                                @endphp

                                @foreach ($estados as $key => $nombre)
                                    <option value="{{ $key }}" {{ $currentStatusId == $key ? 'selected' : '' }}>
                                        {{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estado_orden_id')
                                <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- SEGUNDO BLOQUE: Cliente, Empleado y Total --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        {{-- CAMPO CLIENTE --}}
                        <div>
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                            <select id="cliente_id" name="cliente_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">— Ninguno —</option>
                                @foreach ($clientes as $c)
                                    <option value="{{ $c->id }}"
                                        {{ old('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->codigo }} -
                                        {{ $c->persona->nombre ?? '' }}</option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- CAMPO EMPLEADO --}}
                        <div>
                            <label for="empleado_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Empleado</label>
                            <select id="empleado_id" name="empleado_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">— Ninguno —</option>
                                @foreach ($empleados as $emp)
                                    <option value="{{ $emp->id }}"
                                        {{ old('empleado_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->persona->nombre ?? $emp->id }}</option>
                                @endforeach
                            </select>
                            @error('empleado_id')
                                <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- DISPLAY SUBTOTAL --}}
                        <div class="text-right p-2 rounded-lg border 
                                    bg-indigo-50 border-indigo-200 
                                    dark:bg-indigo-900/50 dark:border-indigo-800">
                            <div class="text-sm mb-1 font-medium 
                                        text-gray-600 dark:text-gray-300">Subtotal estimado</div>
                            <div id="subTotalDisplay" class="text-2xl font-bold 
                                        text-indigo-700 dark:text-indigo-300">0.00</div>
                            <input type="hidden" name="sub_total" id="totalCalculadoInput" value="0.00">
                        </div>
                    </div>

                    <hr class="my-6 border-gray-200 dark:border-gray-700" />

                    <h2 class="text-lg font-bold mb-3 text-gray-700 dark:text-gray-200">Líneas de la Orden (Detalles)</h2>

                    <div id="itemsContainer" class="space-y-3 mb-4"></div>

                    <div class="flex items-center gap-3 mb-6">
                        <button type="button" id="addRowBtn"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Agregar
                            producto</button>
                    </div>

                    {{-- Template para una Fila de Detalle --}}
                    <template id="itemRowTemplate">
                        <div
                            class="grid grid-cols-12 gap-2 items-end p-3 border rounded 
                                   bg-gray-50 border-gray-100 
                                   dark:bg-gray-700/50 dark:border-gray-700 item-row">
                            
                            {{-- Producto --}}
                            <div class="col-span-5">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Producto</label>
                                <select class="w-full px-2 py-2 border rounded producto-select 
                                               border-gray-300 dark:border-gray-600 
                                               bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="">— Seleccionar —</option>
                                </select>
                            </div>

                            {{-- Cantidad --}}
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Cantidad</label>
                                <input type="number" value="1" min="1"
                                    class="w-full px-2 py-2 border rounded cantidad-input text-right
                                           border-gray-300 dark:border-gray-600 
                                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" />
                            </div>

                            {{-- Precio unit. --}}
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Precio unit.</label>
                                <input type="number" step="0.01"
                                    class="w-full px-2 py-2 border rounded precio-input text-right
                                           border-gray-300 dark:border-gray-600 
                                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" />
                            </div>

                            {{-- Monto (Solo Lectura) --}}
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Monto</label>
                                <input type="text" readonly
                                    class="w-full px-2 py-2 border rounded monto-input text-right font-medium 
                                           bg-gray-200 text-gray-800 
                                           dark:bg-gray-600 dark:text-gray-100 border-gray-300 dark:border-gray-600" />
                            </div>

                            {{-- Botón Eliminar --}}
                            <div class="col-span-1 flex justify-center">
                                <button type="button"
                                    class="removeRowBtn inline-flex items-center p-2 bg-red-500 text-white rounded hover:bg-red-600 transition"
                                    title="Eliminar línea">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.86 11.45A2 2 0 0116.14 21H7.86a2 2 0 01-1.99-2.55L5 7m5 4v6m4-6v6m4-10H5" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    {{-- Botones de Acción --}}
                    <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                        <button type="submit"
                            class="px-6 py-3 bg-indigo-600 text-white rounded-md font-semibold hover:bg-indigo-700 transition">Guardar
                            Orden</button>
                        <a href="{{ route('ordenes.index') }}"
                            class="ml-4 px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">Volver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @php
        // 1. Preprocesar la colección de productos con la lógica solicitada por el usuario.
        $productosData = $productos->map(function ($p) {
            return [
                'id' => $p->id,
                'nombre' => $p->item ? $p->item->nombre : 'Sin Nombre',
                'precio' => (float) $p->precio,
            ];
        })->toArray();
    @endphp

    <script>
        (function() {
            // ⭐ CORRECCIÓN APLICADA: Usando la variable pre-procesada
            const productosData = @json($productosData);

            const container = document.getElementById('itemsContainer');
            const tpl = document.getElementById('itemRowTemplate');
            const addRowBtn = document.getElementById('addRowBtn');
            const subTotalDisplay = document.getElementById('subTotalDisplay');
            const totalCalculadoInput = document.getElementById('totalCalculadoInput');

            function escapeHtml(text) {
                return String(text).replace(/[&<>"']/g, function(m) {
                    return ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    } [m]);
                });
            }

            function createSelectOptions() {
                let options = '<option value="">— Seleccionar —</option>';
                options += productosData.map(p =>
                    `<option value="${p.id}" data-precio="${p.precio}">${escapeHtml(p.nombre)} (C$${p.precio.toFixed(2)})</option>`
                ).join('');
                return options;
            }

            function reindexRows() {
                const rows = container.querySelectorAll('.item-row');
                rows.forEach((row, index) => {
                    // Usar 'detalles' como clave para el backend
                    row.querySelector('.producto-select').name = `detalles[${index}][producto_id]`;
                    row.querySelector('.cantidad-input').name = `detalles[${index}][cantidad]`;
                    row.querySelector('.precio-input').name = `detalles[${index}][precio_unitario]`;
                    // Recalcular la línea al reindexar para asegurar que el monto se actualice si es necesario
                    recalcLine(row);
                });
            }

            function recalcLine(row) {
                const productoSelect = row.querySelector('.producto-select');
                const cantidadInput = row.querySelector('.cantidad-input');
                const precioInput = row.querySelector('.precio-input');
                const montoInput = row.querySelector('.monto-input');

                let precio = parseFloat(precioInput.value);
                const cantidad = parseInt(cantidadInput.value) || 0;
                
                // Si el precio está vacío o inválido, intenta usar el precio por defecto del producto seleccionado
                if (isNaN(precio) || precio <= 0) {
                    const opt = productoSelect.selectedOptions[0];
                    precio = opt && opt.dataset.precio ? parseFloat(opt.dataset.precio) : 0;
                    if (!isNaN(precio) && precio > 0 && precioInput.value.trim() === "") {
                        precioInput.value = precio.toFixed(2); // Actualiza el input si se usa el precio del producto y el campo estaba vacío
                    }
                }
                
                const finalPrice = isNaN(precio) ? 0 : precio;
                const monto = (finalPrice * cantidad);

                montoInput.value = isNaN(monto) ? '0.00' : monto.toFixed(2);
                recalcSubtotal();
            }

            function addRow(prefill = {}) {
                const clone = tpl.content.cloneNode(true);
                const select = clone.querySelector('.producto-select');
                select.insertAdjacentHTML('beforeend', createSelectOptions());

                const row = clone.querySelector('.item-row');
                const productoSelect = row.querySelector('.producto-select');
                const cantidadInput = row.querySelector('.cantidad-input');
                const precioInput = row.querySelector('.precio-input');
                const removeBtn = row.querySelector('.removeRowBtn');

                // Prefill logic
                if (prefill.producto_id) {
                    productoSelect.value = prefill.producto_id;
                    // Intenta encontrar el producto por si necesitamos su precio por defecto
                    const selectedProd = productosData.find(p => p.id == prefill.producto_id);
                    // Rellena el precio si no viene en old()
                    if (selectedProd && (!prefill.precio_unitario || parseFloat(prefill.precio_unitario) <= 0)) {
                        prefill.precio_unitario = selectedProd.precio;
                    }
                }
                if (prefill.cantidad) cantidadInput.value = prefill.cantidad;
                // Asegúrate de que el precio unitario se formatee correctamente al rellenar
                if (prefill.precio_unitario) precioInput.value = parseFloat(prefill.precio_unitario).toFixed(2);
                
                // Si el precio todavía está vacío/inválido después del prellenado, usa el precio por defecto del producto si está seleccionado
                if (!precioInput.value && productoSelect.value) {
                    const selectedProd = productosData.find(p => p.id == productoSelect.value);
                    if (selectedProd) {
                        precioInput.value = selectedProd.precio.toFixed(2);
                    }
                }


                // Event Listeners
                productoSelect.addEventListener('change', function() {
                    const opt = this.selectedOptions[0];
                    const precio = opt ? opt.dataset.precio : '';
                    if (precio) precioInput.value = parseFloat(precio).toFixed(2);
                    recalcLine(row);
                });

                cantidadInput.addEventListener('input', () => recalcLine(row));
                precioInput.addEventListener('input', () => recalcLine(row));

                removeBtn.addEventListener('click', function() {
                    row.remove();
                    reindexRows();
                    recalcSubtotal();
                });

                container.appendChild(row);
                reindexRows();
                recalcLine(row);
            }

            function recalcSubtotal() {
                let total = 0;
                container.querySelectorAll('.item-row').forEach(function(row) {
                    const monto = parseFloat(row.querySelector('.monto-input').value || 0);
                    total += isNaN(monto) ? 0 : monto;
                });
                subTotalDisplay.textContent = total.toFixed(2);
                totalCalculadoInput.value = total.toFixed(2);
            }

            addRowBtn.addEventListener('click', function() {
                addRow();
            });

            // Prefill logic using old() data
            const existingOld = @json(old('detalles', []));
            if (Array.isArray(existingOld) && existingOld.length) {
                container.innerHTML = '';
                existingOld.forEach(function(e) {
                    addRow(e);
                });
            } else {
                addRow(); // Añade una fila vacía por defecto
            }

            recalcSubtotal();

        })();
    </script>
</x-app-layout>