<x-app-layout>
    {{-- Contenedor principal con fondo para ambos modos --}}
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        {{-- Título --}}
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Crear Factura</h1>
                        {{-- Subtítulo --}}
                        <p class="text-sm text-gray-600 dark:text-gray-400">Rellene los datos para registrar una nueva factura.</p>
                    </div>
                    {{-- Botón Volver --}}
                    <a href="{{ route('facturas.index') }}" 
                       class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition duration-150">
                       Volver al Listado
                    </a>
                </div>

                {{-- Tarjeta del Formulario --}}
                <div class="bg-white dark:bg-gray-800 rounded shadow p-6">
                    <form action="{{ route('facturas.store') }}" method="POST">
                        @csrf
                        
                        {{-- Nota Venta ID --}}
                        <div class="mb-4">
                            <label for="nota_venta_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nota de Venta Asociada:</label>
                            <select name="nota_venta_id" id="nota_venta_id" 
                                class="w-full px-3 py-2 rounded-md border text-sm 
                                    border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                                    @error('nota_venta_id') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500" required>
                                
                                <option value="" class="text-gray-500 dark:text-gray-400">-- Seleccione una Nota de Venta --</option>
                                @foreach ($notasVenta as $nota)
                                    <option value="{{ $nota->id }}" {{ old('nota_venta_id') == $nota->id ? 'selected' : '' }} class="dark:bg-gray-700">
                                        #{{ $nota->id }} (Total: ${{ number_format($nota->total ?? 0, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('nota_venta_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fecha y Hora --}}
                        <div class="mb-4">
                            <label for="fecha_hora" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha y Hora de Emisión:</label>
                            <input type="datetime-local" name="fecha_hora" id="fecha_hora" 
                                class="w-full px-3 py-2 rounded-md border text-sm 
                                    border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                                    @error('fecha_hora') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500" 
                                value="{{ old('fecha_hora', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('fecha_hora')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Monto --}}
                        <div class="mb-4">
                            <label for="monto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monto Total:</label>
                            <input type="number" step="0.01" name="monto" id="monto" 
                                class="w-full px-3 py-2 rounded-md border text-sm 
                                    border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                                    @error('monto') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500" 
                                value="{{ old('monto') }}" required>
                            @error('monto')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Código Control --}}
                        <div class="mb-6">
                            <label for="codigo_control" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Código de Control (Opcional):</label>
                            <input type="text" name="codigo_control" id="codigo_control" 
                                class="w-full px-3 py-2 rounded-md border text-sm 
                                    border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                                    @error('codigo_control') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500" 
                                value="{{ old('codigo_control') }}">
                            @error('codigo_control')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition duration-150">
                                Guardar Factura
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>