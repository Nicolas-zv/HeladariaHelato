<x-app-layout>
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 text-red-800 rounded">
            <strong>Errores:</strong>
            <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-2xl font-semibold mb-4">Agregar Varias Líneas a Orden {{ $orden->numero_orden }}</h1>

        <div class="bg-white p-6 rounded shadow">
            <form action="{{ route('ordenes.storeLines', $orden) }}" method="POST" id="addLinesForm">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium">Orden</label>
                    <div class="mt-1 font-medium">#{{ $orden->id }} — {{ $orden->numero_orden }}</div>
                </div>

                <h2 class="text-lg font-medium mb-3">Líneas (Productos)</h2>

                <div id="itemsContainer" class="space-y-3 mb-4"></div>

                <div class="flex items-center gap-3 mb-6">
                    <button type="button" id="addRowBtn" class="px-4 py-2 bg-blue-600 text-white rounded">Agregar
                        producto</button>
                </div>

                <template id="itemRowTemplate">
                    <div class="grid grid-cols-12 gap-2 items-end item-row">
                        <div class="col-span-6">
                            <label class="text-sm">Producto</label>
                            <select class="w-full px-2 py-2 border rounded producto-select">
                                <option value="">— Seleccionar —</option>
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="text-sm">Cantidad</label>
                            <input type="number" value="1" min="1"
                                class="w-full px-2 py-2 border rounded cantidad-input" />
                        </div>

                        <div class="col-span-2">
                            <label class="text-sm">Precio unit.</label>
                            <input type="number" step="0.01" class="w-full px-2 py-2 border rounded precio-input" />
                        </div>

                        <div class="col-span-1">
                            <label class="text-sm">Monto</label>
                            <input type="text" readonly
                                class="w-full px-2 py-2 border rounded monto-input bg-gray-50" />
                        </div>

                        <div class="col-span-1">
                            <button type="button"
                                class="removeRowBtn inline-flex items-center px-2 py-2 bg-red-100 text-red-700 rounded">X</button>
                        </div>
                    </div>
                </template>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Guardar líneas</button>
                    <a href="{{ route('ordenes.show', $orden) }}" class="text-gray-600">Volver</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function() {
            const productos = @json(
                $productos->map(function ($p) {
                    return ['id' => $p->id, 'nombre' => $p->nombre, 'precio' => (float) $p->precio];
                }));
            const container = document.getElementById('itemsContainer');
            const tpl = document.getElementById('itemRowTemplate');
            const addRowBtn = document.getElementById('addRowBtn');

            function createSelectOptions() {
                return productos.map(p =>
                    `<option value="${p.id}" data-precio="${p.precio}">${escapeHtml(p.nombre)} (${p.precio.toFixed(2)})</option>`
                    ).join('');
            }

            function reindexRows() {
                const rows = container.querySelectorAll('.item-row');
                rows.forEach((row, index) => {
                    row.querySelector('.producto-select').name = `productos[${index}][producto_id]`;
                    row.querySelector('.cantidad-input').name = `productos[${index}][cantidad]`;
                    row.querySelector('.precio-input').name = `productos[${index}][precio_unitario]`;
                    // monto es solo visual; no es necesario enviarlo
                });
            }

            function addRow(prefill = {}) {
                const clone = tpl.content.cloneNode(true);
                const select = clone.querySelector('.producto-select');
                select.insertAdjacentHTML('beforeend', createSelectOptions());

                const row = clone.querySelector('.item-row');
                const productoSelect = row.querySelector('.producto-select');
                const cantidadInput = row.querySelector('.cantidad-input');
                const precioInput = row.querySelector('.precio-input');
                const montoInput = row.querySelector('.monto-input');
                const removeBtn = row.querySelector('.removeRowBtn');

                if (prefill.producto_id) productoSelect.value = prefill.producto_id;
                if (prefill.cantidad) cantidadInput.value = prefill.cantidad;
                if (prefill.precio_unitario) precioInput.value = parseFloat(prefill.precio_unitario).toFixed(2);

                function recalcLine() {
                    let precio = parseFloat(precioInput.value);
                    if (isNaN(precio) || precio <= 0) {
                        const opt = productoSelect.selectedOptions[0];
                        precio = opt ? parseFloat(opt.dataset.precio || 0) : 0;
                    }
                    const cantidad = parseInt(cantidadInput.value) || 0;
                    const monto = precio * cantidad;
                    montoInput.value = isNaN(monto) ? '0.00' : monto.toFixed(2);
                }

                productoSelect.addEventListener('change', function() {
                    const opt = this.selectedOptions[0];
                    const precio = opt ? opt.dataset.precio : '';
                    if (precio) precioInput.value = parseFloat(precio).toFixed(2);
                    recalcLine();
                });

                cantidadInput.addEventListener('input', recalcLine);
                precioInput.addEventListener('input', recalcLine);

                removeBtn.addEventListener('click', function() {
                    row.remove();
                    reindexRows();
                });

                container.appendChild(row);
                reindexRows();
                recalcLine();
            }

            addRowBtn.addEventListener('click', function() {
                addRow();
            });

            // start with one row
            addRow();

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
        })();
    </script>
</x-app-layout>
