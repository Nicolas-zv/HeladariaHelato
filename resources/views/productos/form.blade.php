@php
    // Usamos $isEdit para determinar la acción y el método
    $isEdit = $isEdit ?? false;
    
    // Inicializar $producto si estamos en modo create para evitar errores de propiedad
    $producto = $producto ?? new \App\Models\Producto();
    
    // Inicializar la data de la receta para evitar errores de variable indefinida en el @forelse
    // En 'create' y en el bloque empty de 'edit' esto será un array vacío.
    $receta_data = $receta_data ?? []; 
@endphp

<form action="{{ $isEdit ? route('productos.update', $producto) : route('productos.store') }}" method="POST">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="bg-white dark:bg-gray-800 p-6 rounded-md shadow-lg space-y-6">
        
        <h2 class="text-xl font-semibold border-b pb-2 text-gray-700 dark:text-gray-300">Datos Principales</h2>
        
        {{-- Campos del Item y Producto --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código*</label>
                {{-- Nota: Usamos $producto->item->codigo para el modo Edit --}}
                <input type="text" name="codigo" id="codigo" 
                       value="{{ old('codigo', $producto->item->codigo ?? '') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700">
                @error('codigo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Producto*</label>
                <input type="text" name="nombre" id="nombre" 
                       value="{{ old('nombre', $producto->nombre ?? '') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700">
                @error('nombre') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            
            {{-- Fila de Precio y Tipo --}}
            <div>
                <label for="precio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Precio de Venta*</label>
                <input type="number" name="precio" id="precio" step="0.01" min="0"
                       value="{{ old('precio', $producto->precio ?? '') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700">
                @error('precio') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="tipo_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo*</label>
                <select name="tipo_id" id="tipo_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700">
                    <option value="">Seleccione Tipo</option>
                    @foreach ($tipos as $tipo)
                        <option value="{{ $tipo->id }}" 
                            {{ old('tipo_id', $producto->tipo_id ?? '') == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Campos Opcionales (Sabor, Menú, Descripción) --}}
        {{-- ... Incluir lógica para sabor_id, menu_id, descripción aquí ... --}}


        {{-- Sección de Receta (CU17: Salidas a Cocina) --}}
        <h2 class="text-xl font-semibold border-b pb-2 pt-4 text-gray-700 dark:text-gray-300">Receta (Consumo de Ingredientes)</h2>

        <div id="receta-container" class="space-y-3">
            
            {{-- Título de columnas --}}
            <div class="grid grid-cols-12 gap-2 text-xs font-semibold uppercase text-gray-500 pb-1 border-b">
                <div class="col-span-6">Ingrediente</div>
                <div class="col-span-4">Cantidad Usada</div>
                <div class="col-span-1 text-center">Unidad</div>
                <div class="col-span-1"></div>
            </div>

            {{-- Iteramos sobre la data existente (edit) o el old input --}}
            @forelse (old('receta', $receta_data) as $index => $detalle)
                @include('productos._receta_linea', [
                    'index' => $index, 
                    'detalle' => (object)$detalle,
                    'ingredientes' => $ingredientes,
                ])
            @empty
                {{-- Línea inicial para CREATE o producto sin receta --}}
                @include('productos._receta_linea', [
                    'index' => 0, // **Importante para corregir Undefined variable $index**
                    'detalle' => (object)['ingrediente_id' => null, 'cantidad' => null, 'unidad' => null],
                    'ingredientes' => $ingredientes,
                ])
            @endforelse
        </div>

        <button type="button" id="add-receta-line" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium flex items-center mt-3">
            <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Añadir Ingrediente a Receta
        </button>

        {{-- Botón de Guardar --}}
        <div class="flex justify-end pt-4 border-t dark:border-gray-700 mt-6">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                {{ $isEdit ? 'Actualizar Producto' : 'Registrar Producto' }}
            </button>
        </div>
    </div>
</form>

{{-- Incluir lógica JavaScript --}}
@include('productos._receta_scripts', ['receta_data' => $receta_data])