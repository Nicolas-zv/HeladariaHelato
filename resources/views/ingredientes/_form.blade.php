@php
    $isEdit = $isEdit ?? false;
    $item = $isEdit ? $ingrediente->item : null;
@endphp

<form action="{{ $isEdit ? route('ingredientes.update', $ingrediente) : route('ingredientes.store') }}" method="POST">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="bg-white dark:bg-gray-800 p-6 rounded-md shadow-lg space-y-4 max-w-xl mx-auto">
        
        @error('db_error') <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">{{ $message }}</div> @enderror

        <h2 class="text-xl font-semibold border-b pb-2 text-gray-700 dark:text-gray-300">Datos de Materia Prima e Item</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            {{-- Código (Heredado de Item) --}}
            <div>
                <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código (Item)*</label>
                <input type="text" name="codigo" id="codigo" 
                       value="{{ old('codigo', $item->codigo ?? '') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700">
                @error('codigo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Nombre (Compartido con Item) --}}
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Ingrediente*</label>
                <input type="text" name="nombre" id="nombre" 
                       value="{{ old('nombre', $item->nombre ?? '') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700">
                @error('nombre') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            {{-- Unidad --}}
            <div>
                <label for="unidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unidad de Medida (gr, kg, ml)</label>
                <input type="text" name="unidad" id="unidad" 
                       value="{{ old('unidad', $ingrediente->unidad ?? '') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700">
                @error('unidad') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Stock --}}
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock Actual*</label>
                <input type="number" name="stock" id="stock" step="0.001" min="0"
                       value="{{ old('stock', $ingrediente->stock ?? 0.000) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700">
                @error('stock') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t dark:border-gray-700 mt-6">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                {{ $isEdit ? 'Actualizar Ingrediente' : 'Crear Ingrediente' }}
            </button>
        </div>
    </div>
</form>