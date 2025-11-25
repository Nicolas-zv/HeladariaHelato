<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold">Editar Factura #{{ $factura->id }}</h1>
                    <p class="text-sm text-gray-600">Modificar los datos de la factura.</p>
                </div>
                <a href="{{ route('facturas.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition duration-150">Volver al Listado</a>
            </div>

            <div class="bg-white rounded shadow p-6">
                <form action="{{ route('facturas.update', $factura) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Nota Venta ID --}}
                    <div class="mb-4">
                        <label for="nota_venta_id" class="block text-sm font-medium text-gray-700 mb-1">Nota de Venta Asociada:</label>
                        <select name="nota_venta_id" id="nota_venta_id" class="w-full px-3 py-2 rounded-md border text-sm @error('nota_venta_id') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">-- Seleccione una Nota de Venta --</option>
                            @foreach ($notasVenta as $nota)
                                <option value="{{ $nota->id }}" {{ old('nota_venta_id', $factura->nota_venta_id) == $nota->id ? 'selected' : '' }}>
                                    #{{ $nota->id }} (Total: ${{ number_format($nota->total ?? 0, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('nota_venta_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha y Hora --}}
                    <div class="mb-4">
                        <label for="fecha_hora" class="block text-sm font-medium text-gray-700 mb-1">Fecha y Hora de Emisión:</label>
                        <input type="datetime-local" name="fecha_hora" id="fecha_hora" class="w-full px-3 py-2 rounded-md border text-sm @error('fecha_hora') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500" 
                            value="{{ old('fecha_hora', $factura->fecha_hora ? $factura->fecha_hora->format('Y-m-d\TH:i') : '') }}" required>
                        @error('fecha_hora')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Monto --}}
                    <div class="mb-4">
                        <label for="monto" class="block text-sm font-medium text-gray-700 mb-1">Monto Total:</label>
                        <input type="number" step="0.01" name="monto" id="monto" class="w-full px-3 py-2 rounded-md border text-sm @error('monto') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('monto', $factura->monto) }}" required>
                        @error('monto')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Código Control --}}
                    <div class="mb-6">
                        <label for="codigo_control" class="block text-sm font-medium text-gray-700 mb-1">Código de Control (Opcional):</label>
                        <input type="text" name="codigo_control" id="codigo_control" class="w-full px-3 py-2 rounded-md border text-sm @error('codigo_control') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('codigo_control', $factura->codigo_control) }}">
                        @error('codigo_control')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md transition duration-150">Actualizar Factura</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>