<x-app-layout>
    <!-- Contenedor principal: añade fondo que cambie con el tema -->
    <div class="container mx-auto px-4 py-8 bg-white dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="flex items-center justify-between mb-6">
            <div>
                <!-- Título -->
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Empleados</h1>
                <!-- Subtítulo -->
                <p class="text-sm text-gray-600 dark:text-gray-400">Listado de empleados registrados</p>
            </div>

            <div class="flex items-center gap-3">
                <!-- Formulario de Búsqueda -->
                <form action="{{ route('empleados.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="search" name="q" value="{{ old('q', $q ?? '') }}" 
                        placeholder="Buscar por nombre, carnet o teléfono..."
                        class="w-64 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 
                               dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-colors duration-200">
                    <button type="submit" 
                        class="px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-300">
                        Buscar
                    </button>
                </form>

                <!-- Botón de Creación -->
                <a href="{{ route('empleados.create') }}" 
                   class="ml-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300">
                    Nuevo Empleado
                </a>
            </div>
        </div>

        {{-- Mensajes de Sesión --}}
        @if(session('success'))
            <div class="mb-4 p-3 rounded-lg shadow-md 
                        bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 transition-colors duration-300">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabla de Empleados -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-colors duration-300">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Carnet</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Teléfono</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dirección</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($empleados as $emp)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $emp->persona->nombre ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $emp->persona->carnet ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $emp->persona->telefono ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $emp->direccion ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $emp->usuario->email ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-right whitespace-nowrap">
                                    <a href="{{ route('empleados.edit', $emp) }}" 
                                       class="px-2 text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-200 transition">
                                        Editar
                                    </a>
                                    <form action="{{ route('empleados.destroy', $emp) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar empleado?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="px-2 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 transition">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No hay empleados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="p-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-600 dark:text-gray-400">Mostrando {{ $empleados->firstItem() ?? 0 }} - {{ $empleados->lastItem() ?? 0 }} de {{ $empleados->total() }}</div>
                <div>{{ $empleados->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>