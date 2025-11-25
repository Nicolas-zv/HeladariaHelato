<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold">{{ $estadoOrden->nombre }}</h1>
                <p class="text-sm text-gray-600">Detalles del estado y órdenes asociadas</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('estado_ordenes.edit', $estadoOrden) }}" class="px-3 py-2 bg-yellow-600 text-white rounded">Editar</a>
                <a href="{{ route('estado_ordenes.index') }}" class="px-3 py-2 bg-gray-200 text-gray-700 rounded">Volver</a>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-sm text-gray-500 mb-2">Órdenes asociadas ({{ $estadoOrden->ordenes->count() }})</h3>

            @if($estadoOrden->ordenes->isEmpty())
                <div class="text-gray-500">No hay órdenes asociadas a este estado.</div>
            @else
                <ul class="divide-y">
                    @foreach($estadoOrden->ordenes as $orden)
                        <li class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-medium">Orden #{{ $orden->id }} - {{ $orden->numero_orden ?? '' }}</div>
                                <div class="text-sm text-gray-600">Cliente: {{ $orden->cliente->codigo ?? '—' }} {{ $orden->cliente->persona->nombre ?? '' }}</div>
                            </div>
                            <div class="text-sm text-gray-600">{{ $orden->created_at ? $orden->created_at->format('Y-m-d H:i') : '' }}</div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>