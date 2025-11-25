<div class="p-4 border rounded-md dark:border-gray-600 dark:bg-gray-700 item-compra" data-index="{{ $index ?? 'INDEX_PLACEHOLDER' }}">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        
        {{-- Item --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 dark:text-gray-300">Item</label>
            <select name="compras[{{ $index ?? 'INDEX_PLACEHOLDER' }}][item_id]" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100" 
                    required 
                    @if(isset($is_edit) && $is_edit) disabled @endif>
                <option value="">Seleccione Item</option>
                @foreach ($items as $item) 
                    <option value="{{ $item->id }}" 
                        {{ (isset($compra) && $compra->item_id == $item->id) ? 'selected' : '' }}>
                        {{ $item->nombre }} ({{ $item->codigo }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Nro Compra --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 dark:text-gray-300">Nro Compra</label>
            <input type="text" name="compras[{{ $index ?? 'INDEX_PLACEHOLDER' }}][nro_compra]" 
                   value="{{ $compra->nro_compra ?? old('compras.' . ($index ?? 0) . '.nro_compra', '') }}" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100" 
                   required
                   @if(isset($is_edit) && $is_edit) disabled @endif>
        </div>

        {{-- Cantidad --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 dark:text-gray-300">Cantidad</label>
            <input type="number" name="compras[{{ $index ?? 'INDEX_PLACEHOLDER' }}][cantidad]" 
                   value="{{ $compra->cantidad ?? old('compras.' . ($index ?? 0) . '.cantidad', '') }}" min="1" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100" 
                   required
                   @if(isset($is_edit) && $is_edit) disabled @endif>
        </div>

        {{-- Costo Unitario --}}
        <div>
            <label class="block text-sm font-medium text-gray-300 dark:text-gray-300">Costo Unitario ($)</label>
            <input type="number" step="0.01" name="compras[{{ $index ?? 'INDEX_PLACEHOLDER' }}][costo]" 
                   value="{{ $compra->costo ?? old('compras.' . ($index ?? 0) . '.costo', '') }}" min="0" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100" 
                   required
                   @if(isset($is_edit) && $is_edit) disabled @endif>
        </div>
        
        {{-- Botón Eliminar --}}
        @if(!isset($is_edit) || !$is_edit)
            <div class="md:col-span-1">
                <button type="button" class="remove-compra p-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition w-full">
                    Quitar
                </button>
            </div>
        @else
            <div class="md:col-span-1 text-center">
                <span class="text-sm text-gray-500 dark:text-gray-400">Fila fija</span>
            </div>
        @endif
    </div>
</div>