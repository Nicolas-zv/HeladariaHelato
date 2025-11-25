<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Editar Menú</h1>

        <div class="bg-white dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
            <form action="{{ route('menus.update', $menu) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="cod_menu" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código</label>
                    <input id="cod_menu" name="cod_menu" value="{{ old('cod_menu', $menu->cod_menu) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500">
                    @error('cod_menu') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="precio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Precio</label>
                        <input id="precio" name="precio" type="number" step="0.01" min="0" value="{{ old('precio', $menu->precio) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        @error('precio') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="dia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Día</label>
                        <input id="dia" name="dia" value="{{ old('dia', $menu->dia) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        @error('dia') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div></div>
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">{{ old('descripcion', $menu->descripcion) }}</textarea>
                    @error('descripcion') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">Actualizar</button>
                    <a href="{{ route('menus.index') }}" class="text-gray-600 dark:text-gray-300">Volver</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>