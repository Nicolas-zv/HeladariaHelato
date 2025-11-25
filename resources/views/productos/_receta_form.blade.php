@php
    // Asegurarse de que $isEdit esté definido
    $isEdit = $isEdit ?? false;
    // Si estamos editando, cargamos los ingredientes asociados
    $receta_actual = $isEdit ? $producto->ingredientes : collect();
    // Se asume que $ingredientes (todos los ingredientes disponibles)
    // está siendo pasado desde el controlador a productos/form.blade.php
@endphp

<h2 class="text-xl font-semibold border-b pb-2 pt-4 text-gray-700 dark:text-gray-300">Receta (Ingredientes)</h2>

<div id="receta-container" class="space-y-3">
    @forelse ($receta_actual as $index => $receta_item)
        {{-- Líneas existentes: Incluye el fragmento de línea de receta (necesitas _receta_linea.blade.php) --}}
        @include('productos._receta_linea', [
            'ingredientes' => $ingredientes,
            'linea_id' => $index,
            'ingrediente_seleccionado' => $receta_item->id,
            'cantidad_usada' => $receta_item->pivot->cantidad,
            'unidad_usada' => $receta_item->pivot->unidad,
        ])
    @empty
        {{-- Si es nuevo o no tiene receta, incluye una línea vacía inicial --}}
        @include('productos._receta_linea', [
            'ingredientes' => $ingredientes,
            'linea_id' => 0,
            'ingrediente_seleccionado' => null,
            'cantidad_usada' => 0,
            'unidad_usada' => null,
        ])
    @endforelse
</div>

<button type="button" id="add-receta-line" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium flex items-center mt-3">
    <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    Añadir Ingrediente
</button>