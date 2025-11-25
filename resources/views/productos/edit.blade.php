<x-app-layout>
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Editar producto</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded">
            <strong>Hay errores en el formulario:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('productos.update', $producto) }}" novalidate>
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Código (Item) -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Código</label>
                <input type="text" name="codigo" value="{{ old('codigo', optional($producto->item)->codigo) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                @error('codigo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Nombre (Item) -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre', optional($producto->item)->nombre) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                @error('nombre') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Precio -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Precio</label>
                <input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                @error('precio') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Tipo -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Tipo</label>
                <select name="tipo_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Seleccione --</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" {{ (int)old('tipo_id', $producto->tipo_id) === $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->descripcion ?? $tipo->cod_tipo ?? "Tipo #{$tipo->id}" }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Sabor -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Sabor (opcional)</label>
                <select name="sabor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Ninguno --</option>
                    @foreach($sabores as $sabor)
                        <option value="{{ $sabor->id }}" {{ (int)old('sabor_id', $producto->sabor_id) === $sabor->id ? 'selected' : '' }}>
                            {{ $sabor->descripcion ?? $sabor->nombre ?? "Sabor #{$sabor->id}" }}
                        </option>
                    @endforeach
                </select>
                @error('sabor_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Menú -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Menú (opcional)</label>
                <select name="menu_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Ninguno --</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}" {{ (int)old('menu_id', $producto->menu_id) === $menu->id ? 'selected' : '' }}>
                            {{ $menu->descripcion ?? $menu->nombre ?? "Menú #{$menu->id}" }}
                        </option>
                    @endforeach
                </select>
                @error('menu_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Descripción -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Descripción (opcional)</label>
            <textarea name="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('descripcion', $producto->descripcion) }}</textarea>
            @error('descripcion') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Receta -->
        <div class="mt-6">
            <h2 class="text-lg font-medium">Receta (ingredientes)</h2>
            <p class="text-sm text-gray-600 mb-3">Agrega/edita los ingredientes que componen el producto. Puedes añadir varias filas.</p>

            <div id="receta-container" class="space-y-3">
                {{-- Prioriza old() sobre receta_data --}}
                @php
                    $rows = old('receta', $receta_data ?? []);
                @endphp

                @if(!empty($rows))
                    @foreach($rows as $i => $r)
                        <div class="receta-row flex gap-2 items-end">
                            <div class="flex-1">
                                <label class="text-sm text-gray-700">Ingrediente</label>
                                <select name="receta[{{ $i }}][ingrediente_id]" class="mt-1 block w-full rounded-md border-gray-300 ingrediente-select">
                                    <option value="">-- Seleccione --</option>
                                    @foreach($ingredientes as $ingrediente)
                                        <option value="{{ $ingrediente->id }}"
                                            data-unidad="{{ $ingrediente->unidad ?? '' }}"
                                            {{ (int)($r['ingrediente_id'] ?? 0) === $ingrediente->id ? 'selected' : '' }}>
                                            {{ optional($ingrediente->item)->nombre ?? $ingrediente->nombre ?? "Ingrediente #{$ingrediente->id}" }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has("receta.$i.ingrediente_id"))
                                    <p class="text-sm text-red-600 mt-1">{{ $errors->first("receta.$i.ingrediente_id") }}</p>
                                @endif
                            </div>

                            <div class="w-40">
                                <label class="text-sm text-gray-700">Cantidad</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" step="0.001" name="receta[{{ $i }}][cantidad]" value="{{ $r['cantidad'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300" />
                                    <span class="unidad-span text-sm text-gray-600">
                                        {{ $r['unidad'] ?? '' }}
                                    </span>
                                </div>
                                @if ($errors->has("receta.$i.cantidad"))
                                    <p class="text-sm text-red-600 mt-1">{{ $errors->first("receta.$i.cantidad") }}</p>
                                @endif
                            </div>

                            <div>
                                <button type="button" class="remove-receta inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-md text-sm">Eliminar</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Fila vacía por defecto --}}
                    <div class="receta-row flex gap-2 items-end">
                        <div class="flex-1">
                            <label class="text-sm text-gray-700">Ingrediente</label>
                            <select name="receta[0][ingrediente_id]" class="mt-1 block w-full rounded-md border-gray-300 ingrediente-select">
                                <option value="">-- Seleccione --</option>
                                @foreach($ingredientes as $ingrediente)
                                    <option value="{{ $ingrediente->id }}" data-unidad="{{ $ingrediente->unidad ?? '' }}">
                                        {{ optional($ingrediente->item)->nombre ?? $ingrediente->nombre ?? "Ingrediente #{$ingrediente->id}" }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-40">
                            <label class="text-sm text-gray-700">Cantidad</label>
                            <div class="flex items-center gap-2">
                                <input type="number" step="0.001" name="receta[0][cantidad]" class="mt-1 block w-full rounded-md border-gray-300" />
                                <span class="unidad-span text-sm text-gray-600"></span>
                            </div>
                        </div>

                        <div>
                            <button type="button" class="remove-receta inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-md text-sm">Eliminar</button>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-3">
                <button id="add-receta" type="button" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md text-sm">
                    + Añadir ingrediente
                </button>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('productos.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Guardar cambios</button>
        </div>
    </form>
</div>

{{-- Template para nuevas filas de receta --}}
<template id="receta-row-template">
    <div class="receta-row flex gap-2 items-end">
        <div class="flex-1">
            <label class="text-sm text-gray-700">Ingrediente</label>
            <select name="_NAME_ING_" class="mt-1 block w-full rounded-md border-gray-300 ingrediente-select">
                <option value="">-- Seleccione --</option>
                @foreach($ingredientes as $ingrediente)
                    <option value="{{ $ingrediente->id }}" data-unidad="{{ $ingrediente->unidad ?? '' }}">
                        {{ optional($ingrediente->item)->nombre ?? $ingrediente->nombre ?? "Ingrediente #{$ingrediente->id}" }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-40">
            <label class="text-sm text-gray-700">Cantidad</label>
            <div class="flex items-center gap-2">
                <input type="number" step="0.001" name="_NAME_CANT_" class="mt-1 block w-full rounded-md border-gray-300" />
                <span class="unidad-span text-sm text-gray-600"></span>
            </div>
        </div>

        <div>
            <button type="button" class="remove-receta inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-md text-sm">Eliminar</button>
        </div>
    </div>
</template>

<script>
    (function () {
        const container = document.getElementById('receta-container');
        const addBtn = document.getElementById('add-receta');
        const template = document.getElementById('receta-row-template').innerHTML;

        // Iniciar índice con el mayor índice de inputs actuales + 1
        let recetaIndex = (function() {
            const rows = container.querySelectorAll('.receta-row');
            if (!rows.length) return 0;
            let max = -1;
            rows.forEach(row => {
                const sel = row.querySelector('select[name^="receta"]');
                if (!sel) return;
                const name = sel.getAttribute('name'); // ejemplo: receta[3][ingrediente_id]
                const match = name.match(/^receta\[(\d+)\]\[ingrediente_id\]$/);
                if (match) {
                    const idx = parseInt(match[1], 10);
                    if (idx > max) max = idx;
                }
            });
            return max + 1;
        })();

        function setUnidadFromSelect(selectEl) {
            const unidad = selectEl.selectedOptions[0]?.dataset?.unidad ?? '';
            const row = selectEl.closest('.receta-row');
            const span = row?.querySelector('.unidad-span');
            if (span) span.textContent = unidad || '';
        }

        function addRow(prefill = {}) {
            const newHtml = template
                .replace(/_NAME_ING_/g, receta[${recetaIndex}][ingrediente_id])
                .replace(/_NAME_CANT_/g, receta[${recetaIndex}][cantidad]);
            const wrapper = document.createElement('div');
            wrapper.innerHTML = newHtml;
            const row = wrapper.firstElementChild;

            // Prefill values if vienen
            if (prefill.ingrediente_id) {
                const sel = row.querySelector(select[name="receta[${recetaIndex}][ingrediente_id]"]);
                if (sel) {
                    sel.value = prefill.ingrediente_id;
                    setUnidadFromSelect(sel);
                }
            }
            if (prefill.cantidad) {
                const inp = row.querySelector(input[name="receta[${recetaIndex}][cantidad]"]);
                if (inp) inp.value = prefill.cantidad;
            }

            container.appendChild(row);
            recetaIndex++;
        }

        // Delegación para remover filas
        container.addEventListener('click', function (e) {
            if (e.target && e.target.matches('.remove-receta')) {
                const row = e.target.closest('.receta-row');
                if (row) row.remove();
            }
        });

        // Actualizar unidad cuando se cambia el select (delegación)
        container.addEventListener('change', function (e) {
            if (e.target && e.target.matches('.ingrediente-select')) {
                setUnidadFromSelect(e.target);
            }
        });

        addBtn.addEventListener('click', function () {
            addRow();
        });

        // Inicializar unidades en filas ya existentes
        document.querySelectorAll('#receta-container .ingrediente-select').forEach(sel => {
            setUnidadFromSelect(sel);
        });

    })();
</script>
</x-app-layout>