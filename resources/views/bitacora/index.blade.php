<x-app-layout>

    <div class="col-span-full xl:col-span-8 bg-gray-100 dark:bg-slate-900 shadow-lg rounded-sm">
        <!-- Mensaje de éxito o error -->
        @if (session('success'))
            <div class="bg-green-500 text-white text-sm font-semibold p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-500 text-white text-sm font-semibold p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        <header class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
            <h2 class="text-center font-semibold text-slate-800 dark:text-slate-100">Bitacora</h2>

            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mt-2 mb-2">
                <!-- Input de búsqueda -->
                <div class="flex items-center space-x-2 mb-4 lg:mb-0">
                    <input id="searchInput" type="text"
                        class="w-full lg:w-64 px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white text-xs font-medium"
                        placeholder="Buscar..." onkeyup="searchTable()">

                    <!-- Botón Exportar -->
                    <form method="GET" action="{{ route('bitacora.export') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white dark:text-gray-200 font-semibold px-2 py-1 rounded-md text-xs sm:text-xs flex items-center">
                            <i class="fas fa-file-excel mr-1"></i> Exportar
                        </button>
                    </form>
                    <!-- Botón eliminar filtracion -->
                    <form method="POST" action="{{ route('bitacora.eliminar') }}">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white dark:text-gray-200 font-semibold px-2 py-1 rounded-md text-xs sm:text-xs flex items-center">
                            <i class="fas fa-trash-alt mr-1"></i> Eliminar
                        </button>
                    </form>


                </div>
            </div>


            <!-- Filtro por fechas -->
            <form method="GET" action="{{ route('bitacora.index') }}"
                class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 w-full lg:w-auto">
                <!-- Fecha de inicio -->
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <label for="start_date"
                        class="text-sm font-medium text-gray-700 mr-2 dark:text-gray-300 mb-1 sm:mb-0 sm:mr-2">Fecha
                        de inicio</label>
                    <input id="start_date" name="start_date" type="date" value="{{ request('start_date') }}"
                        class="px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white text-xs font-medium">
                </div>

                <!-- Fecha de fin -->
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <label for="end_date"
                        class="text-sm font-medium text-gray-700 ml-2 mr-2 dark:text-gray-300 mb-1 sm:mb-0 sm:mr-2">Fecha
                        de fin</label>
                    <input id="end_date" name="end_date" type="date" value="{{ request('end_date') }}"
                        class="px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white text-xs font-medium">
                </div>

                <!-- Botón de filtrado -->
                <div class="flex justify-end sm:justify-start">
                    <button type="submit"
                        class="flex-shrink-0 bg-blue-500 hover:bg-blue-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white dark:text-gray-200 font-semibold px-1.5 py-1 rounded-md text-xs sm:text-xs ml-1 mr-1">
                        Filtrar
                    </button>
                </div>
            </form>

    </div>
    </header>
    <div class="p-4">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full dark:text-slate-300">
                <!-- Table header -->
                <thead
                    class="text-xs uppercase text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-700 dark:bg-opacity-50 rounded-sm">
                    <tr>
                        <th class="p-2">
                            <div class="font-semibold text-left">Id</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-left">Nombre</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Actividad</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Fecha</div>
                        </th>
                        <th class="p-2">
                            <div class="font-semibold text-center">Acciones</div>
                        </th>

                    </tr>
                </thead>
                <!-- Table body -->
                <tbody id="searchTableBody" class="text-sm font-medium divide-y divide-slate-100 dark:divide-slate-700">
                    <!-- Row -->
                    @foreach ($activities as $activity)
                        <tr>
                            <td class="p-2">
                                <div class="text-center text-emerald-500">{{ $activity->id }}</div>
                            </td>

                            <td class="p-2">
                                <div class="text-center text-slate-800 dark:text-slate-100">
                                    @if ($activity->causer)
                                        {{ $activity->causer->name }}
                                    @else
                                        Usuario no disponible
                                    @endif
                                </div>
                            </td>
                            <td class="p-2">
                                <div class="text-center text-emerald-500">{{ $activity->description }}</div>
                            </td>
                            <td class="p-2">
                                <div class="text-center text-center text-slate-800 dark:text-slate-100">
                                    {{ $activity->created_at }}</div>
                            </td>

                            <td class="p-2">
                                <div class="flex justify-center space-x-4">
                                    <!-- Botón de Eliminar -->
                                    <a title="ELIMINAR" href="{{ route('bitacora.destroy', $activity->id) }}"
                                        onclick="event.preventDefault(); if (confirm('¿Estás seguro de eliminar esta actividad?')) { document.getElementById('delete-form-{{ $activity->id }}').submit(); }"
                                        class="rounded-lg p-2 text-white hover:scale-125 transition-transform delay-75">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-5 h-5 text-red-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </a>
                                    <!-- Formulario de eliminación -->
                                    <form id="delete-form-{{ $activity->id }}"
                                        action="{{ route('bitacora.destroy', $activity->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                </div>
                            </td>


                        </tr>
                        <!-- Row -->
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>
    </div>



    @push('scripts')
        <script>
            function searchTable() {
                var input, filter, table, tbody, tr, td, i, j, txtValue;
                input = document.getElementById("searchInput");
                filter = input.value.toUpperCase();
                table = document.querySelector("table");
                tbody = document.getElementById("searchTableBody");
                tr = tbody.getElementsByTagName("tr");

                for (i = 0; i < tr.length; i++) {
                    tr[i].style.display = "none";
                    td = tr[i].getElementsByTagName("td");

                    for (j = 0; j < td.length; j++) {
                        if (td[j]) {
                            txtValue = td[j].textContent || td[j].innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                                break;
                            }
                        }
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
