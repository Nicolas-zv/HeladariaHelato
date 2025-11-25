<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Crear Proveedor</h1>

        <div class="bg-white dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
            <form action="{{ route('proveedores.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código</label>
                    <input id="codigo" name="codigo" value="{{ old('codigo') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    @error('codigo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                    <input id="descripcion" name="descripcion" value="{{ old('descripcion') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    @error('descripcion') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="ubicacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ubicación</label>
                    <input id="ubicacion" name="ubicacion" value="{{ old('ubicacion') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    @error('ubicacion') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
                    <input id="telefono" name="telefono" value="{{ old('telefono') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    @error('telefono') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Guardar</button>
                    <a href="{{ route('proveedores.index') }}" class="text-gray-600 dark:text-gray-300">Volver</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>