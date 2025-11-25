{{--
    Partial para mostrar el Top N de productos más populares.
    Requiere la variable $datos (Collection de productos) y $periodo (string).
--}}

@if ($datos && $datos->isEmpty())
    <div class="text-center p-6 bg-yellow-50 rounded-lg border border-yellow-300 text-yellow-700 font-medium shadow-sm">
        <p>
            {{ __('No se encontraron datos de ventas para el período') }} 
            <span class="font-semibold">{{ $periodo ?? 'seleccionado' }}</span>.
        </p>
    </div>
@else
    <div class="space-y-4">
        @foreach ($datos as $index => $producto)
            @php
                $posicion = $index + 1;
                $bgColor = $posicion === 1 ? 'bg-yellow-100 border-yellow-500' : ($posicion === 2 ? 'bg-gray-100 border-gray-400' : 'bg-white border-blue-100');
                $textColor = $posicion === 1 ? 'text-yellow-800' : ($posicion === 2 ? 'text-gray-700' : 'text-blue-600');
            @endphp

            <div class="{{ $bgColor }} border-l-4 rounded-lg shadow-md p-4 flex items-center justify-between transition duration-150 ease-in-out hover:shadow-lg">
                
                <div class="flex items-center space-x-4">
                    {{-- Posición Destacada --}}
                    <div class="w-10 h-10 flex items-center justify-center text-xl font-extrabold rounded-full {{ $bgColor }} {{ $textColor }} border-2">
                        #{{ $posicion }}
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">{{ $producto['nombre'] }}</h4>
                        <p class="text-sm text-gray-500">Código: {{ $producto['codigo'] ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="text-right flex-shrink-0">
                    <p class="text-md font-semibold text-gray-800">
                        {{ number_format($producto['total_vendido'], 0, '.', ',') }} {{ __('uds vendidas') }}
                    </p>
                    <p class="text-sm font-medium {{ $textColor }}">
                        {{ __('Ingreso: $') }}{{ $producto['total_ingreso'] }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
@endif