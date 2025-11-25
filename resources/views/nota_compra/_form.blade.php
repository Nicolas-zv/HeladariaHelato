<div class="space-y-6">
    {{-- Campo Código --}}
    <<div>
        <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código de la Nota</label>
        <input type="text" name="codigo" id="codigo" value="{{ old('codigo', $notaCompra->codigo ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" required>
        @error('codigo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Campo Fecha/Hora --}}
    <div>
        <label for="fecha_hora" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha y Hora</label>
        <input type="datetime-local" name="fecha_hora" id="fecha_hora" 
               value="{{ old('fecha_hora', ($notaCompra->fecha_hora ?? now())->format('Y-m-d\TH:i')) }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
        @error('fecha_hora')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Campo Proveedor --}}
    <div>
        <label for="proveedor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Proveedor</label>
        <select name="proveedor_id" id="proveedor_id" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
            <option value="">Seleccione un Proveedor</option>
            @foreach ($proveedores as $proveedor) 
                <option value="{{ $proveedor->id }}" 
                    {{ old('proveedor_id', $notaCompra->proveedor_id ?? '') == $proveedor->id ? 'selected' : '' }}>
                    {{ $proveedor->descripcion }} ({{ $proveedor->codigo }})
                </option>
            @endforeach
        </select>
        @error('proveedor_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- =================================================== --}}
    {{--                   DETALLES DE COMPRA                --}}
    {{-- =================================================== --}}
    
    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 pt-4">Detalles de la Compra (Ítems)</h3>
    
    <div id="compras-container" class="space-y-4">
        {{-- Aquí se insertarán las filas dinámicas de compra mediante JavaScript --}}
        @if (isset($notaCompra) && $notaCompra->exists && $notaCompra->compras->count() > 0)
            {{-- SOLO EN EDICIÓN: Si la nota ya existe, mostramos los detalles existentes --}}
            @foreach ($notaCompra->compras as $index => $compra)
                @include('nota_compra.partials.compra_row', [
                    'index' => $index,
                    'compra' => $compra,
                    'items' => $items,
                    'is_edit' => true // Indicamos que es modo edición
                ])
            @endforeach
        @endif
        
        @error('compras')<p class="mt-2 text-sm text-red-600 font-bold">Debe agregar al menos un ítem de compra.</p>@enderror
    </div>
    
    <div class="mt-4">
        {{-- El botón que JS usará para clonar/crear la fila --}}
        @if (!isset($notaCompra) || !$notaCompra->exists)
        <button type="button" id="add-compra" class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 text-sm">
            + Agregar Ítem de Compra
        </button>
        @else
        <p class="text-gray-500 dark:text-gray-400">En el modo de edición, solo se permite modificar la cabecera. Para cambios de ítems, considere eliminar y volver a registrar.</p>
        @endif
    </div>
</div>
</div>