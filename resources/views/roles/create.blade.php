<x-app-layout>
    <div class="container mx-auto py-8 px-4">
        <div class="max-w-xl mx-auto bg-white dark:bg-slate-700 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-6 text-gray-900 dark:text-white">Crear rol</h2>

            @if ($errors->any())
                <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-red-700">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="name">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-800 dark:focus:border-indigo-600">
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('roles.index') }}" class="mr-2 rounded-lg px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-slate-600 dark:hover:bg-slate-500 text-gray-800 dark:text-white">
                        Cancelar
                    </a>
                    <button type="submit" class="rounded-lg px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
