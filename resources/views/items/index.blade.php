<x-app-layout>
    <!-- Contenedor principal: añade fondo que cambie con el tema -->
    <div class="container mx-auto px-4 py-8 bg-white dark:bg-gray-900 min-h-screen transition-colors duration-300">
        
        <div class="flex justify-between items-center mb-6">
            <!-- Título -->
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Gestión de Ítems de Inventario</h1>
            
            {{-- Botón de Creación (Descomentado en tu original) --}}
            {{-- <a href="{{ route('items.create') }}" 
               class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition duration-300">
                + Crear Nuevo Ítem
            </a> --}}
        </div>

        {{-- Búsqueda --}}
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-inner">
            <form action="{{ route('items.index') }}" method="GET" class="flex items-center space-x-3">
                
                <input type="text" name="q" placeholder="Buscar por código o nombre..." 
                        value="{{ $q ?? '' }}"
                        class="flex-grow px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 
                                rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-colors duration-200" />
                
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition duration-300 shadow-md">
                    Buscar
                </button>
                
                @if($q)
                    <a href="{{ route('items.index') }}" 
                       class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300 shadow-md">
                        Limpiar
                    </a>
                @endif
            </form>
        </div>

        {{-- Mensajes de Sesión --}}
        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 
                        px-4 py-3 rounded-lg relative mb-4 shadow-md" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabla de Ítems --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-colors duration-300">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                            {{-- <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Producto Asociado</th> --}}
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock Físico</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Disponible</th>
                            {{-- <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th> --}}
                        </tr>
                    </thead>
                    
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($items as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $item->codigo }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $item->nombre }}</td>
                                {{-- <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-400">{{ $item->producto->nombre ?? 'N/A' }}</td> --}}
                                <td class="px-4 py-3 text-sm text-right font-medium text-gray-900 dark:text-gray-200">{{ number_format($item->cantidad, 0) }}</td>
                                <td class="px-4 py-3 text-sm text-center">
                                    @if($item->disponible)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                      bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">Sí</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                      bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100">No</span>
                                    @endif
                                </td>
                                
                                {{-- COLUMNA DE ACCIONES (Descomentada en tu original) --}}
                                {{-- <td class="px-4 py-3 text-sm text-right whitespace-nowrap">
                                    <a href="{{ route('items.show', $item->id) }}" title="Ver detalles"
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-3 transition">
                                        👁️
                                    </a>
                                    
                                    <a href="{{ route('items.edit', $item->id) }}" title="Editar"
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 mr-3 transition">
                                        ✏️
                                    </a>
                                    
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('¿Está seguro de que desea eliminar este ítem?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Eliminar"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 transition">
                                            🗑️
                                        </button>
                                    </form>
                                </td> --}}
                                {{-- FIN COLUMNA DE ACCIONES --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No se encontraron ítems que coincidan con su búsqueda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                {{-- Nota: El aspecto del paginador depende de la configuración de Laravel, 
                     pero el contenedor ya es compatible con Dark Mode. --}}
                {{ $items->links() }}
            </div>
        </div>
    </div>
</x-app-layout>