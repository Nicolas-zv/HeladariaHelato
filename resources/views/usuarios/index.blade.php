<x-app-layout>

    <div class="col-span-full xl:col-span-8 bg-white dark:bg-slate-900 shadow-lg rounded-sm">
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
            <h2 class="text-center font-semibold text-slate-800 dark:text-slate-100">Usuarios</h2>
            {{-- <div class="mt-4 mb-4">
                <a id="modal-toggle-button"
                    class="flex-shrink-0 bg-green-800 hover:bg-green-900 dark:bg-slate-700 dark:hover:bg-gray-600 text-white dark:text-gray-200 font-semibold px-3 py-2 rounded-md text-sm sm:text-sm ml-1 mr-1">
                    <i class="fas fa-plus mr-1"></i> Añadir
                </a>
            </div> --}}
            <div class="flex flex-1 items-center mb-2 sm:mb-0">
                @hasanyrole('Administrador')
                    <a id="modal-toggle-button"
                        class="flex-shrink-0 bg-[#1F7F15] hover:bg-[#499F3B] {{--  dark:hover:bg-slate-600--}}  text-white dark:text-gray-200 font-semibold px-1.5 py-1 rounded-md text-xs sm:text-xs ml-1 mr-1">
                        <i class="fas fa-plus mr-1"></i> Añadir
                    </a>
                @endhasanyrole
                <input id="searchInput" type="text"
                    class="px-3 py-1 border rounded-md w-full sm:w-auto dark:bg-gray-800 dark:text-white text-xs font-medium mr-2"
                    style="font-size: 12px;" placeholder="Buscar..." onkeyup="searchTable()">

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
                                <div class="font-semibold text-left">id</div>
                            </th>
                            <th class="p-2">
                                <div class="font-semibold text-left">name</div>
                            </th>
                            <th class="p-2">
                                <div class="font-semibold text-center">email</div>
                            </th>
                            <th class="p-2">
                                <div class="font-semibold text-center">rol</div>
                            </th>
                            <th class="p-2">
                                <div class="font-semibold text-center">Acciones</div>
                            </th>

                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody  id="searchTableBody" class="text-sm font-medium divide-y divide-slate-100 dark:divide-slate-700">
                        <!-- Row -->
                        @foreach ($usuarios as $u)
                            <tr>
                                <td class="p-2">
                                    <div class="text-center text-emerald-500">{{ $u->id }}</div>
                                </td>
                                <td class="p-2">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 mr-2 sm:mr-3" width="36" height="36"
                                            viewBox="0 0 36 36">
                                            <circle fill="#24292E" cx="18" cy="18" r="18" />
                                            <path
                                                d="M18 10.2c-4.4 0-8 3.6-8 8 0 3.5 2.3 6.5 5.5 7.6.4.1.5-.2.5-.4V24c-2.2.5-2.7-1-2.7-1-.4-.9-.9-1.2-.9-1.2-.7-.5.1-.5.1-.5.8.1 1.2.8 1.2.8.7 1.3 1.9.9 2.3.7.1-.5.3-.9.5-1.1-1.8-.2-3.6-.9-3.6-4 0-.9.3-1.6.8-2.1-.1-.2-.4-1 .1-2.1 0 0 .7-.2 2.2.8.6-.2 1.3-.3 2-.3s1.4.1 2 .3c1.5-1 2.2-.8 2.2-.8.4 1.1.2 1.9.1 2.1.5.6.8 1.3.8 2.1 0 3.1-1.9 3.7-3.7 3.9.3.4.6.9.6 1.6v2.2c0 .2.1.5.6.4 3.2-1.1 5.5-4.1 5.5-7.6-.1-4.4-3.7-8-8.1-8z"
                                                fill="#FFF" />
                                        </svg>
                                        <div class="text-slate-800 dark:text-slate-100">{{ $u->name }}</div>
                                    </div>
                                </td>

                                <td class="p-2">
                                    <div class="text-center text-slate-800 dark:text-slate-100">{{ $u->email }}
                                    </div>
                                </td>
                                <td class="p-2">
                                    <div class="text-center text-slate-800 dark:text-slate-100">
                                        <!-- Mostrar el rol del usuario -->
                                        @if ($u->roles->isNotEmpty())
                                            {{ $u->roles->pluck('name')->implode(', ') }}
                                        @else
                                            Sin rol asignado
                                        @endif
                                    </div>
                                </td>

                                <td class="p-2">
                                    <div class="flex justify-center space-x-4">
                                        <!-- Botón de Editar -->
                                        <a title="EDITAR" href="{{ route('usuarios.edit', $u->id) }}"
                                            class="rounded-lg p-2 text-white hover:scale-125 transition-transform delay-75">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" class="w-5 h-5 text-cyan-800">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </a>

                                        <!-- Botón de Eliminar -->
                                        <a title="ELIMINAR" href="{{ route('usuarios.destroy', $u->id) }}"
                                            onclick="event.preventDefault(); if (confirm('¿Estás seguro de eliminar este usuario?')) { document.getElementById('delete-form-{{ $u->id }}').submit(); }"
                                            class="rounded-lg p-2 text-white hover:scale-125 transition-transform delay-75">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" class="w-5 h-5 text-red-600">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </a>

                                        <!-- Formulario de eliminación -->
                                        <form id="delete-form-{{ $u->id }}"
                                            action="{{ route('usuarios.destroy', $u->id) }}" method="POST"
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

    <div id="crud-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full" style="left: 50%; transform: translateX(-50%);">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Registrar Usuarios
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        id="modal-close-button">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Cerrar modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('usuarios.store') }}" method="POST">
                    @csrf
                    <div class="p-4 md:p-5">
                        <div class="col-span-1">
                            <x-label for="name" :value="__('Nombre')" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                required />
                        </div>
                        <div class="col-span-1">
                            <x-label for="email" :value="__('Email')" />
                            <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                                required />
                        </div>
                        <div class="col-span-1">
                            <x-label for="password" :value="__('contraseña')" />
                            <x-input id="password" class="block mt-1 w-full" type="text" name="password"
                                required />
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="flex-shrink-0 bg-cyan-500 hover:bg-blue-900 dark:bg-slate-700 dark:hover:bg-gray-600 text-white dark:text-gray-200 font-semibold px-3 py-2 rounded-md text-sm sm:text-sm ml-1 mr-1">
                                Agregar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal end -->

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
        <script>
            // JavaScript para abrir y cerrar el primer modal
            const modalToggleButton = document.getElementById('modal-toggle-button');
            const modalCloseButton = document.getElementById('modal-close-button');
            const crudModal = document.getElementById('crud-modal');

            modalToggleButton.addEventListener('click', function() {
                crudModal.classList.toggle('hidden');
            });

            modalCloseButton.addEventListener('click', function() {
                crudModal.classList.add('hidden');
            });
        </script>
    @endpush
</x-app-layout>
