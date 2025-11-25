<div class="receta-line grid grid-cols-12 gap-2 items-center">
    <div class="col-span-6">
        <select name="receta[{{ $index }}][ingrediente_id]" required
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 select-ingrediente">
            <option value="">-- Seleccione Ingrediente --</option>
            @foreach ($ingredientes as $ingrediente)
                <option value="{{ $ingrediente->id }}" 
                    {{ old("receta.$index.ingrediente_id", $detalle->ingrediente_id) == $ingrediente->id ? 'selected' : '' }} 
                    data-unidad="{{ $ingrediente->unidad }}">
                    {{ $ingrediente->item->nombre }}
                </option>
            @endforeach
        </select>
        @error("receta.$index.ingrediente_id") <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
    
    <div class="col-span-4">
        <input type="number" name="receta[{{ $index }}][cantidad]" step="0.001" min="0.001" placeholder="Cantidad" required
               value="{{ old("receta.$index.cantidad", $detalle->cantidad) }}"
               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 input-cantidad">
        @error("receta.$index.cantidad") <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Mostramos la unidad base del ingrediente (solo visualmente) --}}
    <div class="col-span-1 text-center text-sm text-gray-600 unidad-label">
        {{-- Si el ingrediente está seleccionado, mostramos su unidad --}}
        @php
            $selectedIngrediente = $ingredientes->firstWhere('id', old("receta.$index.ingrediente_id", $detalle->ingrediente_id));
        @endphp
        {{ $selectedIngrediente->unidad ?? '' }}
    </div>

    <div class="col-span-1 flex justify-end">
        <button type="button" class="remove-receta-line text-red-600 hover:text-red-800">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
        </button>
    </div>
</div>