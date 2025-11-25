<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-2xl font-semibold mb-4">Línea #{{ $linea->id }}</h1>

        <div class="bg-white p-6 rounded shadow">
            <div class="mb-4">
                <strong>Orden:</strong> #{{ $linea->orden_id }} - {{ $linea->orden->numero_orden ?? '' }}
            </div>
            <div class="mb-4">
                <strong>Producto:</strong> {{ $linea->producto->nombre ?? '—' }}
            </div>
            <div class="mb-4">
                <strong>Cantidad:</strong> {{ $linea->cantidad }}
            </div>
            <div class="mb-4">
                <strong>Precio unit.:</strong> {{ number_format($linea->precio_unitario, 2) }}
            </div>
            <div class="mb-4">
                <strong>Monto:</strong> {{ number_format($linea->monto, 2) }}
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('orden-productos.index', ['orden_id' => $linea->orden_id]) }}" class="px-3 py-2 bg-gray-200 rounded">Volver</a>
                <a href="{{ route('orden-productos.edit', $linea) }}" class="px-3 py-2 bg-yellow-600 text-white rounded">Editar</a>
            </div>
        </div>
    </div>
</x-app-layout>
