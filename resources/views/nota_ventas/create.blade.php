    <x-app-layout>
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-6">🧾 Generar Nota de Venta (Orden #{{ $orden->id }})</h1>

            <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-xl">
                {{-- Usa la ruta post para almacenar la Nota de Venta --}}
                <form action="{{ route('nota_ventas.store_from_orden', $orden->id) }}" method="POST">
                    @csrf

                    <h2 class="text-xl font-semibold mb-4">Detalles de la Orden</h2>
                    <p>Total Estimado: ${{ number_format($orden->sub_total ?? 0, 2) }}</p>
                    <p>Mesa: {{ $orden->mesa->numero ?? 'N/A' }}</p>
                    <p>Cliente Actual: {{ $orden->cliente->persona->nombre ?? 'N/A' }}</p>
                    
                    <hr class="my-4">

                    {{-- Campo Tipo de Pago --}}
                    <div class="mb-4">
                        <label for="tipo_pago" class="block text-sm font-medium text-gray-700">Tipo de Pago</label>
                        <select name="tipo_pago" id="tipo_pago" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="">Seleccione un método</option>
                            <option value="efectivo" {{ old('tipo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                            <option value="tarjeta" {{ old('tipo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                            <option value="transferencia" {{ old('tipo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                            <option value="otro" {{ old('tipo_pago') == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('tipo_pago')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Campo Asignar Cliente (Opcional) --}}
                    <div class="mb-4">
                        <label for="cliente_id" class="block text-sm font-medium text-gray-700">Asignar Cliente (Opcional)</label>
                        <select name="cliente_id" id="cliente_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="{{ $orden->cliente_id }}">Mantener Cliente Actual ({{ $orden->cliente->persona->nombre ?? 'N/A' }})</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->persona->nombre ?? $cliente->codigo }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 shadow-md">
                            Pagar y Generar Nota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-app-layout>