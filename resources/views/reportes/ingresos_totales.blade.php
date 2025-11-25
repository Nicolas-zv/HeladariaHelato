<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-gray-100 mb-10 text-center tracking-tight">
                Reporte Financiero
            </h1>

            <!-- Contenedor del Filtro de Fechas -->
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-2xl mb-8 border border-indigo-200 dark:border-indigo-700">
                <form action="{{ route('reportes.ingresos.calcular') }}" method="POST"
                    class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 items-end">
                    @csrf

                    {{-- Filtro Fecha Inicio --}}
                    <div class="flex-1 w-full">
                        <label for="inicio"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Fecha de
                            Inicio</label>
                        <input type="date" name="inicio" id="inicio" required value="{{ $fechaInicio }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 p-2.5 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out shadow-sm">
                        @error('inicio')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Filtro Fecha Fin --}}
                    <div class="flex-1 w-full">
                        <label for="fin"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Fecha de
                            Fin</label>
                        <input type="date" name="fin" id="fin" required value="{{ $fechaFin }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 p-2.5 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out shadow-sm">
                        @error('fin')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botón Generar Reporte -->
                    <div class="w-full md:w-auto mt-4 md:mt-0">
                        <button type="submit"
                            class="w-full md:w-auto px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Generar Reporte
                        </button>
                    </div>
                </form>
            </div>

            @if (!empty((array) $resultados))
                <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-2xl">
                    <h2
                        class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100 border-b pb-3 border-gray-200 dark:border-gray-700">
                        Análisis del Periodo ({{ $fechaInicio }} al {{ $fechaFin }})
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                        {{-- Tarjeta 1: Ingresos Brutos --}}
                        <div
                            class="bg-blue-50 dark:bg-blue-900/40 p-5 rounded-xl border-l-4 border-blue-500 shadow-md transform hover:scale-[1.01] transition duration-200">
                            <p class="text-sm font-medium text-gray-500 dark:text-blue-300">Ingresos Brutos</p>
                            <p class="text-3xl font-extrabold text-blue-800 dark:text-blue-200 mt-1">
                                ${{ number_format($resultados->ingresosBrutos, 2) }}
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Ventas totales sin deducciones.</p>
                        </div>

                        {{-- Tarjeta 2: Costos Totales --}}
                        <div
                            class="bg-red-50 dark:bg-red-900/40 p-5 rounded-xl border-l-4 border-red-500 shadow-md transform hover:scale-[1.01] transition duration-200">
                            <p class="text-sm font-medium text-gray-500 dark:text-red-300">Costo Total (COGS Aprox.)</p>
                            <p class="text-3xl font-extrabold text-red-800 dark:text-red-200 mt-1">
                                ${{ number_format($resultados->costoTotalProduccion, 2) }}
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Suma de costos de ingredientes de
                                producción.</p>
                        </div>

                        {{-- Tarjeta 3: Utilidad Bruta --}}
                        <!-- Clase condicional para color verde/rojo según la ganancia -->
                        @php
                            $isPositive = $resultados->utilidadBruta >= 0;
                            $utilidadColorClass = $isPositive
                                ? 'text-green-800 dark:text-green-200'
                                : 'text-red-800 dark:text-red-200';
                            $utilidadBgClass = $isPositive
                                ? 'bg-green-50 dark:bg-green-900/40 border-green-500'
                                : 'bg-red-50 dark:bg-red-900/40 border-red-500';
                            $utilidadTextColorClass = $isPositive ? 'dark:text-green-300' : 'dark:text-red-300';
                        @endphp
                        <div
                            class="p-5 rounded-xl border-l-4 shadow-md transform hover:scale-[1.01] transition duration-200 {{ $utilidadBgClass }}">
                            <p class="text-sm font-medium text-gray-500 {{ $utilidadTextColorClass }}">Utilidad Bruta
                                (Ganancia)</p>
                            <p class="text-3xl font-extrabold mt-1 {{ $utilidadColorClass }}">
                                ${{ number_format($resultados->utilidadBruta, 2) }}
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Ganancia antes de gastos
                                operativos.</p>
                        </div>

                    </div>
                </div>
            @endif

        </div>
    </div>


</x-app-layout>
