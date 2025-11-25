<x-app-layout> {{-- Asumiendo que usas un layout principal --}}
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-7xl mx-auto">

        <h1 class="text-2xl md:text-3xl text-slate-800 dark:text-slate-100 font-bold mb-6">
            Editar Ítem: {{ $item->nombre }}
        </h1>

        @include('items._form', [
            'action' => route('items.update', $item->id),
            'method' => 'PUT', // O 'PATCH'
            // La variable $item y $productos deben venir del ItemController@edit
        ])

    </div>
</x-app-layout>