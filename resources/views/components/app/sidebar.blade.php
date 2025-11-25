<div>
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-slate-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true" x-cloak></div>

    <!-- Sidebar -->
    <div id="sidebar"
        class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:!w-64 shrink-0 p-4 transition-all duration-200 ease-in-out"
        :class="{ 'bg-red-900 dark:bg-slate-800': true, 'translate-x-0': sidebarOpen, '-translate-x-64': !sidebarOpen }"
        @click.outside="sidebarOpen = false" @keydown.escape.window="sidebarOpen = false" x-cloak="lg">

        <!-- Sidebar header -->
        <div class="flex justify-between mb-3 pr-3 sm:px-2">
            <!-- Close button -->
            <button class="lg:hidden text-slate-500 hover:text-slate-400" @click.stop="sidebarOpen = !sidebarOpen"
                aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>

        </div>

        <!-- Links -->
        <div class="space-y-3">
            <!-- Pages group -->
            <div>
                <h3 class="text-xs text-center uppercase text-slate-100 font-semibold pl-3">

                    <span class="hidden lg:block lg:sidebar-expanded:hidden 4x2:hidden text-center w-6"
                        aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">HELADERIA SOLÉ</span>
                </h3>
                <nav class="space-y-8">
                    <ul class="mt-3">
                        @hasanyrole('Administrador|Empleado|Secretario')
                            <!-- 1. PANEL PRINCIPAL -->
                            <div class="space-y-1">
                                <h3 class="text-xs uppercase font-semibold text-gray-400 dark:text-gray-500 ml-4 mb-2">
                                    Principal
                                </h3>
                                <ul>
                                    <li
                                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['dashboard'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                        <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['dashboard'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                            href="{{ route('dashboard') }}">
                                            <div class="flex items-center">
                                                <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['dashboard'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 22 22">
                                                    <path fill="currentColor"
                                                        d="M4 19v2c0 .5523.44772 1 1 1h14c.5523 0 1-.4477 1-1v-2H4Z" />
                                                    <path fill-rule="evenodd"
                                                        d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span
                                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dashboard</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endhasanyrole

                        @hasanyrole('Administrador')
                            <!-- 2. ADMINISTRACIÓN DE SISTEMA -->
                            <div class="space-y-1">
                                <h3 class="text-xs uppercase font-semibold text-gray-400 dark:text-gray-500 ml-4 mb-2">
                                    Administración</h3>
                                <ul>
                                    <li
                                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['usuarios'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                        <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['usuarios'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                            href="{{ route('usuarios.index') }}">
                                            <div class="flex items-center">
                                                <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['usuarios'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 22 22">
                                                    <path fill-rule="evenodd"
                                                        d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z"
                                                        clip-rule="evenodd" />
                                                </svg>


                                                <span
                                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Usuarios</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li
                                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['roles'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                        <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['roles'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                            href="{{ route('roles.index') }}">
                                            <div class="flex items-center">
                                                <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['roles'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 22 22">
                                                    <path fill-rule="evenodd"
                                                        d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span
                                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Roles</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endhasanyrole
                        <!-- 3. GESTIÓN DE INVENTARIO Y MENÚ -->
                        <div class="space-y-1">
                            <h3 class="text-xs uppercase font-semibold text-gray-400 dark:text-gray-500 ml-4 mb-2">
                                Inventario y Menú</h3>
                            <ul>
                                <li
                                    class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['productos'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                    <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['productos'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                        href="{{ route('productos.index') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['productos'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 22 22">
                                                <path fill-rule="evenodd"
                                                    d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                                    clip-rule="evenodd" />
                                            </svg>


                                            <span
                                                class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Productos</span>
                                        </div>
                                    </a>
                                </li>
                                @hasanyrole('Administrador')
                                    <li
                                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['items'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                        <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['items'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                            href="{{ route('items.index') }}">
                                            <div class="flex items-center">
                                                <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['items'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 22 22">
                                                    <path fill-rule="evenodd"
                                                        d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span
                                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Item</span>
                                            </div>
                                        </a>
                                    </li>
                                @endhasanyrole

                                <li
                                    class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['ingredientes'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                    <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['ingredientes'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                        href="{{ route('ingredientes.index') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['ingredientes'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 22 22">
                                                <path fill-rule="evenodd"
                                                    d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                                    clip-rule="evenodd" />
                                            </svg>


                                            <span
                                                class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Ingredientes</span>
                                        </div>
                                    </a>
                                </li>

                                <li
                                    class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['proveedores'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                    <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['proveedores'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                        href="{{ route('proveedores.index') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['proveedores'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 22 22">
                                                <path fill-rule="evenodd"
                                                    d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                                    clip-rule="evenodd" />
                                            </svg>


                                            <span
                                                class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Proveedores</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                            <li
                                class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['menus'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['menus'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                    href="{{ route('menus.index') }}">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['menus'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 22 22">
                                            <path fill-rule="evenodd"
                                                d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                                clip-rule="evenodd" />
                                        </svg>


                                        <span
                                            class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Menu</span>
                                    </div>
                                </a>
                            </li>
                        </div>
                        <!-- 4. OPERACIONES DIARIAS -->
                        <div class="space-y-1">
                            <h3 class="text-xs uppercase font-semibold text-gray-400 dark:text-gray-500 ml-4 mb-2">
                                Operaciones</h3>
                            <ul>
                                <li
                                    class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['empleados'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                    <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['empleados'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                        href="{{ route('empleados.index') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['empleados'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 22 22">
                                                <path fill-rule="evenodd"
                                                    d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span
                                                class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Empleados</span>
                                        </div>
                                    </a>
                                </li>
                                <li
                                    class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['clientes'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                    <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['clientes'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                        href="{{ route('clientes.index') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['clientes'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 22 22">
                                                <path fill-rule="evenodd"
                                                    d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                                    clip-rule="evenodd" />
                                            </svg>


                                            <span
                                                class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Clientes</span>
                                        </div>
                                    </a>
                                </li>
                                @hasanyrole('Administrador|mesero')
                                    <li
                                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['mesas'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                        <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['mesas'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                            href="{{ route('mesas.index') }}">
                                            <div class="flex items-center">
                                                <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['mesas'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 22 22">
                                                    <path fill-rule="evenodd"
                                                        d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span
                                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Mesas</span>
                                            </div>
                                        </a>
                                    </li>
                                @endhasanyrole

                                <li
                                    class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['ordenes'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                    <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['ordenes'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                        href="{{ route('ordenes.index') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['ordenes'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 22 22">
                                                <path fill-rule="evenodd"
                                                    d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                                    clip-rule="evenodd" />
                                            </svg>


                                            <span
                                                class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Ordenes</span>
                                        </div>
                                    </a>
                                </li>

                                <!-- Elemento: Nota Ventas, Nota Compra, Nota Salida, Factura, etc. -->
                                <li
                                    class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['notaas_compra'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                    <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['notas_compra'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                        href="{{ route('nota-compra.index') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['notas_compra'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 22 22">
                                                <path fill-rule="evenodd"
                                                    d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                                    clip-rule="evenodd" />
                                            </svg>


                                            <span
                                                class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Nota
                                                Compra</span>
                                        </div>
                                    </a>
                                </li>
                                <li
                                    class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['notas_salida'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                    <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['notas_salida'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                        href="{{ route('notas-salida.index') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['notas_salida'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 22 22">
                                                <path fill-rule="evenodd"
                                                    d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                                    clip-rule="evenodd" />
                                            </svg>


                                            <span
                                                class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Nota
                                                Salida</span>
                                        </div>
                                    </a>
                                </li>
                                <li
                                    class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['nota_ventas'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                    <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['nota_ventas'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                        href="{{ route('nota-ventas.index') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['nota_ventas'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 22 22">
                                                <path fill-rule="evenodd"
                                                    d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span
                                                class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Nota
                                                Ventas</span>
                                        </div>
                                    </a>
                                </li>
                                @hasanyrole('Administrador|Cajero')
                                    <li
                                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['facturas'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                        <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['facturas'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                            href="{{ route('facturas.index') }}">
                                            <div class="flex items-center">
                                                {{-- Icono SVG para Reporte/Finanzas (Ejemplo de un gráfico de barras) --}}
                                                <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['facturas'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M16 11V3H8v8H6v2h12v-2h-2zm-6-6h4v6h-4V5zm10 14h-5v5h-2v-5H4v2H2v-2h2v-2h2v2h2v-2h2v2h2v-2h2v2h2v-2h2v2z" />
                                                </svg>


                                                <span
                                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Factura</span>
                                            </div>
                                        </a>
                                    </li>
                                @endhasanyrole
                                <!-- ... Tus <li> para Documentos de Operación ... -->
                            </ul>
                        </div>
                        <!-- 5. ANALÍTICA Y CONTROL -->
                        <div class="space-y-1">
                            <h3 class="text-xs uppercase font-semibold text-gray-400 dark:text-gray-500 ml-4 mb-2">
                                Reportes y Control</h3>
                            <ul>
                                <li
                                    class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['reporteproductos'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                    <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['reporteproductos'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                        href="{{ route('reportes.productos.populares.index') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['reporteproductos'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 22 22">
                                                <path fill-rule="evenodd"
                                                    d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                                    clip-rule="evenodd" />
                                            </svg>


                                            <span
                                                class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Productos
                                                Populares</span>
                                        </div>
                                    </a>
                                </li>
                                <li
                                    class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['valoraciones'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                    <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['valoraciones'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                        href="{{ route('valoraciones.index') }}">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['valoraciones'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 22 22">
                                                <path fill-rule="evenodd"
                                                    d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                                    clip-rule="evenodd" />
                                            </svg>


                                            <span
                                                class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Valoraciones</span>
                                        </div>
                                    </a>
                                </li>

                                @hasanyrole('Administrador')
                                    <li
                                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['bitacora'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                        <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (!in_array(Request::segment(1), ['bitacora'])) {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                            href="{{ route('bitacora.index') }}">
                                            <div class="flex items-center">
                                                <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['bitacora'])) {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 22 22">
                                                    <path fill-rule="evenodd"
                                                        d="M10 2a3 3 0 0 0-3 3v1H5a3 3 0 0 0-3 3v2.382l1.447.723.005.003.027.013.12.056c.108.05.272.123.486.212.429.177 1.056.416 1.834.655C7.481 13.524 9.63 14 12 14c2.372 0 4.52-.475 6.08-.956.78-.24 1.406-.478 1.835-.655a14.028 14.028 0 0 0 .606-.268l.027-.013.005-.002L22 11.381V9a3 3 0 0 0-3-3h-2V5a3 3 0 0 0-3-3h-4Zm5 4V5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1h6Zm6.447 7.894.553-.276V19a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3v-5.382l.553.276.002.002.004.002.013.006.041.02.151.07c.13.06.318.144.557.242.478.198 1.163.46 2.01.72C7.019 15.476 9.37 16 12 16c2.628 0 4.98-.525 6.67-1.044a22.95 22.95 0 0 0 2.01-.72 15.994 15.994 0 0 0 .707-.312l.041-.02.013-.006.004-.002.001-.001-.431-.866.432.865ZM12 10a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H12Z"
                                                        clip-rule="evenodd" />
                                                </svg>


                                                <span
                                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Bitacora</span>
                                            </div>
                                        </a>
                                    </li>

                                    <li
                                        class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))] @if (in_array(Request::segment(1), ['reporte-ingresos'])) {{ 'from-violet-100/[0.12] dark:from-violet-100/[0.24] to-violet-100/[0.04]' }} @endif">
                                        <a class="block text-gray-100 dark:text-gray-100 truncate transition @if (Request::segment(1) !== 'reportes') {{ 'hover:text-orange-400 dark:hover:text-orange-400' }} @endif"
                                            href="{{ route('reportes.ingresos.index') }}">
                                            <div class="flex items-center">
                                                {{-- Icono SVG --}}
                                                <svg class="shrink-0 fill-current @if (Request::segment(1) === 'reportes' && Request::segment(2) === 'ingresos') {{ 'text-orange-400' }}@else{{ 'text-gray-200 dark:text-gray-200' }} @endif"
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M16 11V3H8v8H6v2h12v-2h-2zm-6-6h4v6h-4V5zm10 14h-5v5h-2v-5H4v2H2v-2h2v-2h2v2h2v-2h2v2h2v-2h2v2h2v-2h2v2z" />
                                                </svg>

                                                <span
                                                    class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Reporte
                                                    Financiero</span>
                                            </div>
                                        </a>
                                    </li>
                                @endhasanyrole
                            </ul>
                        </div>
                        @hasanyrole('Administrador')
                        @endhasanyrole
                        @hasanyrole('Administrador|Cajero')
                        @endhasanyrole

                    </ul>
                </nav>
            </div>
        </div>

        <!-- Expand / collapse button -->
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
            <div class="px-3 py-2">
                <button @click="sidebarExpanded = !sidebarExpanded">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <svg class="w-6 h-6 fill-current sidebar-expanded:rotate-180" viewBox="0 0 24 24">
                        <path class="text-slate-400"
                            d="M19.586 11l-5-5L16 4.586 23.414 12 16 19.414 14.586 18l5-5H7v-2z" />
                        <path class="text-slate-600" d="M3 23H1V1h2z" />
                    </svg>
                </button>
            </div>
        </div>

    </div>
</div>
