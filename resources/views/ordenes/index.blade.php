<x-app-layout>
{{-- Contenedor principal con fondo para ambos modos --}}
{{-- bg-gray-100 (Light) | dark:bg-gray-900 (Dark) --}}
<div class="bg-gray-100 dark:bg-gray-900 min-h-screen">
<div class="container mx-auto px-4 py-8">
<div class="flex items-center justify-between mb-6">
<div>
{{-- Título y Subtítulo adaptados al modo oscuro --}}
<h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Órdenes de Servicio</h1>
<p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Gestión y listado de todas las órdenes registradas.</p>
</div>

            <div class="flex items-center gap-3">
                {{-- Formulario de Búsqueda --}}
                <form action="{{ route('ordenes.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="search" name="q" value="{{ old('q', $q ?? '') }}"
                        placeholder="Buscar por número..."
                        {{-- Clases de Dark Mode para el input: fondo, borde, texto y placeholder --}}
                        class="w-64 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                            focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 
                            bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                    <button
                        class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition duration-150 shadow-md">Buscar</button>
                </form>
                
                {{-- Botones de Acción --}}
                <a href="{{ route('orden-productos.index') }}"
                    class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-150 shadow-md">
                    Ver Productos
                </a>
                
                <a href="{{ route('ordenes.create') }}"
                    class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-150 shadow-md">
                    + Nueva Orden
                </a>
            </div>
        </div>

        {{-- Mensajes de Sesión (Ya estaban adaptados para Dark Mode) --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 rounded-lg border border-green-200 dark:border-green-600 shadow-sm">
                {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 rounded-lg border border-red-200 dark:border-red-600 shadow-sm">
                {{ session('error') }}</div>
        @endif

        {{-- Tarjeta de la Tabla --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    
                    {{-- Cabecera de la Tabla --}}
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Número</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Empleado</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    
                    {{-- Cuerpo de la Tabla --}}
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($ordenes as $orden)
                            {{-- Hover de la fila adaptado --}}
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-100">
                                {{-- Texto de celdas adaptado --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                    {{ $orden->numero_orden }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $orden->fecha_hora ? $orden->fecha_hora->format('d/m/Y H:i') : '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $orden->cliente?->persona?->nombre ?? ($orden->cliente?->codigo ?? '—') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $orden->empleado?->persona?->nombre ?? '—' }}</td>

                                {{-- Lógica del Badge de Estado (Confirmando dark mode en las clases dinámicas) --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $estadoNombre = strtolower($orden->estado->nombre ?? 'desconocido');
                                        // Clases para LIGHT Mode (por defecto) y DARK Mode (prefijo dark:)
                                        $colorClass = 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100'; // Default
                                        
                                        if (str_contains($estadoNombre, 'pendiente') || $estadoNombre == 'en preparacion') {
                                            $colorClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                        } elseif ($estadoNombre == 'lista' || $estadoNombre == 'servido') {
                                            $colorClass = 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300';
                                        } elseif (str_contains($estadoNombre, 'pagada') || str_contains($estadoNombre, 'cerrada')) {
                                            $colorClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                        } elseif (str_contains($estadoNombre, 'cancelada')) {
                                            $colorClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                        }
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ $orden->estado->nombre ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900 dark:text-gray-100">
                                    ${{ number_format($orden->sub_total, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        {{-- Botón Marcar como Servido --}}
                                        @if (
                                            $orden->estado &&
                                                !in_array(strtolower($orden->estado->nombre ?? ''), ['servida', 'pagada/cerrada', 'pagada', 'cancelada']))
                                            <form action="{{ route('ordenes.mark_as_served', $orden->id) }}"
                                                method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit"
                                                    class="text-xs px-3 py-1 bg-teal-500 text-white rounded-md hover:bg-teal-600 transition shadow-sm"
                                                    onclick="return confirm('¿Confirmar que esta orden está Servida y lista para facturar?');">
                                                    ✅ Servido
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Botones de Enlace (con adaptación dark:hover:text) --}}
                                        <a href="{{ route('ordenes.show', $orden) }}"
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200 transition duration-150 p-1 rounded-md border border-indigo-100 dark:border-indigo-900/50">Detalle</a>
                                        <a href="{{ route('ordenes.edit', $orden) }}"
                                            class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-200 transition duration-150 p-1 rounded-md border border-yellow-100 dark:border-yellow-900/50">Editar</a>
                                        <form action="{{ route('ordenes.destroy', $orden) }}"
                                            method="POST" class="inline-block"
                                            onsubmit="return confirm('¿Está seguro de eliminar esta orden? Esta acción es irreversible.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200 transition duration-150 p-1 rounded-md border border-red-100 dark:border-red-900/50">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-lg text-gray-500 dark:text-gray-400">No hay órdenes
                                    registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Paginación y Resumen --}}
            <div class="p-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Mostrando {{ $ordenes->firstItem() ?? 0 }} - {{ $ordenes->lastItem() ?? 0 }} de
                    {{ $ordenes->total() }} registros.
                </div>
                <div>
                    {{-- La paginación debe ser compatible con Tailwind para heredar el estilo dark: --}}
                    {{ $ordenes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


</x-app-layout>