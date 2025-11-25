<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $cliente->codigo }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $cliente->persona->nombre ?? '— sin nombre —' }}</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('clientes.edit', $cliente) }}" class="px-3 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">Editar</a>
                <a href="{{ route('clientes.index') }}" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md">Volver</a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md p-6 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-300">Código</h3>
                <div class="mt-1 text-gray-900 dark:text-gray-100">{{ $cliente->codigo }}</div>
            </div>

            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-300">Nombre</h3>
                <div class="mt-1 text-gray-900 dark:text-gray-100">{{ $cliente->persona->nombre ?? '—' }}</div>
            </div>

            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-300">Carnet</h3>
                <div class="mt-1 text-gray-900 dark:text-gray-100">{{ $cliente->persona->carnet ?? '—' }}</div>
            </div>

            <div>
                <h3 class="text-sm text-gray-500 dark:text-gray-300">Teléfono</h3>
                <div class="mt-1 text-gray-900 dark:text-gray-100">{{ $cliente->persona->telefono ?? '—' }}</div>
            </div>
        </div>
    </div>
</x-app-layout>