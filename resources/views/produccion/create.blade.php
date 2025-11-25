<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6 text-center">Registrar Producción (CU23)</h1>
            
            <form action="{{ route('produccion.store') }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-md shadow-lg space-y-6">
                @csrf
                
                {{-- Mensajes de Alerta --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">{{ session('error') }}</div>
                @endif

                <div class="space-y-4">
                    {{-- Campo Producto a Producir --}}
                    <div>
                        <label for="producto_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Producto a Producir*</label>
                        <select name="producto_id" id="producto_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700">
                            <option value="">-- Seleccione un Producto --</option>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                    {{ $producto->item->nombre }} (Stock actual: {{ $producto->item->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('producto_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo Cantidad a Producir --}}
                    <div>
                        <label for="cantidad_producida" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad a Producir (Unidades)*</label>
                        <input type="number" name="cantidad_producida" id="cantidad_producida" min="1" required
                               value="{{ old('cantidad_producida') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700">
                        @error('cantidad_producida') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t dark:border-gray-700 mt-6">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        Registrar Producción
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>