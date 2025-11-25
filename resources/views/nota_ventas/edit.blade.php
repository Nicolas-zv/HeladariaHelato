<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Editar Nota de Venta</h1>

        <div class="bg-white dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
            <form action="{{ route('nota-ventas.update', $notaVenta) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="fecha_hora" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha / Hora</label>
                    <input type="datetime-local" id="fecha_hora" name="fecha_hora" value="{{ old('fecha_hora', $notaVenta->fecha_hora ? $notaVenta->fecha_hora->format('Y-m-d\TH:i') : '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    @error('fecha_hora') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total</label>
                        <input type="number" step="0.01" min="0" id="total" name="total" value="{{ old('total', $notaVenta->total) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        @error('total') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tipo_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de pago</label>
                        <input id="tipo_pago" name="tipo_pago" value="{{ old('tipo_pago', $notaVenta->tipo_pago) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        @error('tipo_pago') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="cliente_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                        <select id="cliente_id" name="cliente_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                            <option value="">— Ninguno —</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ (old('cliente_id', $notaVenta->cliente_id) == $cliente->id) ? 'selected' : '' }}>
                                    {{ $cliente->codigo }} - {{ $cliente->persona->nombre ?? '—' }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="empleado_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Empleado</label>
                        <select id="empleado_id" name="empleado_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                            <option value="">— Ninguno —</option>
                            @foreach($empleados as $empleado)
                                <option value="{{ $empleado->id }}" {{ (old('empleado_id', $notaVenta->empleado_id) == $empleado->id) ? 'selected' : '' }}>
                                    {{ $empleado->persona->nombre ?? $empleado->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('empleado_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="mesa_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mesa</label>
                        <select id="mesa_id" name="mesa_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                            <option value="">— Ninguna —</option>
                            @foreach($mesas as $mesa)
                                <option value="{{ $mesa->id }}" {{ (old('mesa_id', $notaVenta->mesa_id) == $mesa->id) ? 'selected' : '' }}>
                                    Mesa {{ $mesa->numero }} @if($mesa->ubicacion) ({{ $mesa->ubicacion }}) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('mesa_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4 flex items-center gap-3">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="pagado" value="1" class="form-checkbox" {{ old('pagado', $notaVenta->pagado) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pagado</span>
                    </label>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">Actualizar</button>
                    <a href="{{ route('nota-ventas.index') }}" class="text-gray-600 dark:text-gray-300">Volver</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>