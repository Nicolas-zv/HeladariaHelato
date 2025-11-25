<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <h1 class="text-2xl font-semibold mb-4">Editar Línea #{{ $ordenProducto->id }}</h1>

        <div class="bg-white p-6 rounded shadow">
            <form action="{{ route('orden-productos.update', $ordenProducto) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium">Orden</label>
                    <div class="mt-1">#{{ $ordenProducto->orden_id }} - {{ $ordenProducto->orden->numero_orden ?? '' }}</div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Producto</label>
                    <select name="producto_id" class="w-full px-3 py-2 border rounded">
                        @foreach($productos as $p)
                            <option value="{{ $p->id }}" {{ (old('producto_id', $ordenProducto->producto_id) == $p->id) ? 'selected' : '' }} data-precio="{{ $p->precio }}">{{ $p->nombre }} ({{ number_format($p->precio,2) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium">Cantidad</label>
                        <input type="number" name="cantidad" min="1" value="{{ old('cantidad', $ordenProducto->cantidad) }}" class="w-full px-3 py-2 border rounded" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Precio unit. (opcional)</label>
                        <input type="number" step="0.01" name="precio_unitario" value="{{ old('precio_unitario', $ordenProducto->precio_unitario) }}" class="w-full px-3 py-2 border rounded" />
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded">Actualizar línea</button>
                    <a href="{{ route('orden-productos.index', ['orden_id' => $ordenProducto->orden_id]) }}" class="text-gray-600">Volver</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
