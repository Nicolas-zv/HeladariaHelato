<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Registrar Nueva Valoración</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Completa los detalles de la experiencia del cliente.</p>
        </div>

        @if(session('error'))
            <div class="mb-4 p-4 rounded-md bg-red-50 dark:bg-red-900/30 border border-red-100 dark:border-red-800 text-red-800 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-sm p-6 lg:p-8">
            <!-- Asume la ruta 'valoraciones.store' definida en web.php -->
            <form action="{{ route('valoraciones.store') }}" method="POST">
                @csrf

                <!-- Nombre del Cliente -->
                <div class="mb-5">
                    <label for="nombre_cliente" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del Cliente</label>
                    <input type="text" name="nombre_cliente" id="nombre_cliente" value="{{ old('nombre_cliente', 'Anónimo') }}" required
                        class="w-full px-4 py-2 rounded-md bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('nombre_cliente')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ID de Nota de Venta (Opcional, asume que 'nota_venta_id' es el nombre de la columna) -->
                <div class="mb-5">
                    <label for="nota_venta_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ID de Nota de Venta (Opcional)</label>
                    <input type="number" name="nota_venta_id" id="nota_venta_id" value="{{ old('nota_venta_id') }}"
                        class="w-full px-4 py-2 rounded-md bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('nota_venta_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Calificación General (1-5) -->
                <div class="mb-5">
                    <label for="experiencia_general" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Calificación General (1 - 5)</label>
                    <select name="experiencia_general" id="experiencia_general" required
                        class="w-full px-4 py-2 rounded-md bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Seleccione una calificación</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ old('experiencia_general') == $i ? 'selected' : '' }}>{{ $i }} - {{ $i == 5 ? 'Excelente' : ($i == 1 ? 'Pésimo' : 'Aceptable') }}</option>
                        @endfor
                    </select>
                    @error('experiencia_general')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comentario -->
                <div class="mb-6">
                    <label for="comentario" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Comentario (Opcional)</label>
                    <textarea name="comentario" id="comentario" rows="3"
                        class="w-full px-4 py-2 rounded-md bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">{{ old('comentario') }}</textarea>
                    @error('comentario')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('valoraciones.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Guardar Valoración
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>