<x-app-layout>
    <div class="container mx-auto py-8 px-4">
        <div class="bg-white dark:bg-slate-700 shadow rounded-lg p-6">
            <h2 class="text-1xl text-center font-bold mb-6 text-gray-900 dark:text-white">Editar Usuario</h2>

            <!-- Formulario de edición -->
            <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nombre -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $usuario->name) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-800 dark:focus:border-indigo-600">
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-800 dark:focus:border-indigo-600">
                </div>

                <!-- Roles -->
                <div class="mb-4">
                    <label for="roles" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rol</label>
                    <select name="roles" id="roles"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-800 dark:focus:border-indigo-600">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" @if ($role->name == $userRoles[0]) selected @endif>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Botón de actualizar -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-red-500 hover:bg-red-900 text-white dark:bg-slate-600 dark:hover:bg-slate-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
