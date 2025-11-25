<x-app-layout>
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6 text-center">Registrar Salida de Item</h1>
            
            <form action="{{ route('notas-salida.store') }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-md shadow-lg space-y-4">
                @csrf
                
                {{-- Item --}}
                <div>
                    <label for="item_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item a Dar de Baja*</label>
                    <select name="item_id" id="item_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700">
                        <option value="">-- Seleccione Producto o Ingrediente --</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nombre }} ({{ $item->codigo }}) - Stock: {{ $item->cantidad }}
                            </option>
                        @endforeach
                    </select>
                    @error('item_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                
                {{-- Cantidad --}}
                <div>
                    <label for="cantidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad*</label>
                    <input type="number" name="cantidad" id="cantidad" min="1" required
                           value="{{ old('cantidad', 1) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700">
                    @error('cantidad') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Código Opcional --}}
                <div>
                    <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código de Nota (Opcional)</label>
                    <input type="text" name="codigo" id="codigo" 
                           value="{{ old('codigo') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700">
                    @error('codigo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="flex justify-end pt-4 border-t dark:border-gray-700 mt-6">
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Confirmar Salida
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>