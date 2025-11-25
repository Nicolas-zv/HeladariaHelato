<x-app-layout> {{-- Asumiendo que usas un layout principal --}}
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-7xl mx-auto">

        <h1 class="text-2xl md:text-3xl text-slate-800 dark:text-slate-100 font-bold mb-6">Crear Nuevo Ítem</h1>

        @include('items._form', [
            'action' => route('items.store'),
            'method' => 'POST',
            // Pasa un objeto Item vacío para que los campos de valor funcionen sin errores:
            'item' => new \App\Models\Item(), 
            // La variable $productos debe venir del ItemController@create
        ])

    </div>
</x-app-layout>