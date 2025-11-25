<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reporte de Productos Populares (Top 3)') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4 border-b pb-2 text-indigo-600 dark:text-indigo-400 dark:border-gray-700">
                    {{ __('Filtro de Reporte Mensual') }}
                </h3>

                <form action="{{ route('reportes.productos.populares.generar_mensual') }}" method="POST" 
                      class="flex flex-wrap items-end gap-4 mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border dark:border-gray-600">
                    @csrf
                    
                    @php
                        // Asignación de variables de vista
                        $mes_seleccionado = old('mes', $mes ?? now()->month);
                        $anio_seleccionado = old('anio', $anio ?? now()->year);
                        $meses = $meses ?? [
                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
                            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                        ];
                    @endphp
                    
                    <div class="flex-grow min-w-[120px]">
                        <label for="mes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Mes') }}</label>
                        <select id="mes" name="mes" required 
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">{{ __('Seleccione el Mes') }}</option>
                            @foreach ($meses as $num => $nombre)
                                <option value="{{ $num }}" {{ $mes_seleccionado == $num ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('mes')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex-grow min-w-[120px]">
                        <label for="anio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Año') }}</label>
                        <input type="number" id="anio" name="anio" required 
                            value="{{ $anio_seleccionado }}" 
                            min="2000" max="{{ date('Y') + 1 }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('anio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex-shrink-0">
                        <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150 ease-in-out">
                            {{ __('Generar Reporte') }}
                        </button>
                    </div>
                </form>

                @if (session('success'))
                    <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                {{-- SECCIÓN REPORTE MENSUAL (CU24) --}}
                @if ($reporteMensual)
                    <div class="mt-8">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4 border-b pb-2 text-indigo-800 dark:text-indigo-400 dark:border-gray-700">
                            {{ __('Top 3 de Productos Más Populares en') }} {{ $periodo_formateado }}
                        </h3>
                        @include('REPORTEPRODUCTOS._producto_popular_list', [
                            'datos' => $reporteMensual, 
                            'periodo' => $periodo_formateado
                        ])
                        <hr class="my-8 border-gray-300 dark:border-gray-700">
                    </div>
                @endif
                
                {{-- SECCIÓN REPORTE HISTÓRICO GENERAL (CU23) --}}
                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4 border-b pb-2 dark:border-gray-700">
                        {{ __('Top 3 de Productos Más Populares Histórico (General) - CU23') }}
                    </h3>
                    @include('reporteproductos._producto_popular_list', [
                        'datos' => $productosPopulares, 
                        'periodo' => 'Histórico General'
                    ])
                </div>

            </div>
        </div>
    </div>
</x-app-layout>