<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">
            Registrar Nuevo Ingrediente (Materia Prima)
        </h1>

        @php
            // 1. Establecemos el contexto de CREACIÓN (isEdit = false)
            $isEdit = false;
            
            // 2. Creamos un modelo Ingrediente vacío para evitar errores de referencia
            $ingrediente = new \App\Models\Ingrediente(); 
        @endphp

        {{-- Incluimos el formulario parcial y le pasamos el contexto --}}
        @include('ingredientes._form', [
            'isEdit' => $isEdit,
            'ingrediente' => $ingrediente,
            // Nota: Si el formulario necesitara otros datos (ej. $unidades), pásalos aquí
        ])

        {{-- Aquí se puede añadir un botón de "Volver" --}}
        <div class="mt-6 text-center">
            <a href="{{ route('ingredientes.index') }}" 
               class="text-sm text-gray-600 dark:text-gray-400 hover:underline">
                ← Volver al Listado de Ingredientes
            </a>
        </div>
    </div>
</x-app-layout>