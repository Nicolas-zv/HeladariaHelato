<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-lg">
        <h1 class="text-2xl font-semibold mb-4">Crear Estado de Orden</h1>

        <div class="bg-white p-6 rounded shadow">
            <form action="{{ route('estado_ordenes.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium">Nombre</label>
                    <input name="nombre" value="{{ old('nombre') }}" required class="w-full px-3 py-2 border rounded" />
                    @error('nombre') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Guardar</button>
                    <a href="{{ route('estado_ordenes.index') }}" class="text-gray-600">Volver</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>