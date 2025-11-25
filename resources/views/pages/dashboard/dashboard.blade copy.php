<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Welcome banner -->
        <x-dashboard.welcome-banner />

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">


{{--
            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">

                <!-- Filter button -->
                <x-dropdown-filter align="right" />
                <x-datepicker />


            </div> --}}

        </div>

        <!-- Cards -->
        <div class="grid grid-cols-12 gap-6">

            <x-dashboard.dashboard-card-01 :ventas="$ventas" />

            <x-dashboard.dashboard-card-02 :productos="$productos" />

            <x-dashboard.dashboard-card-03 :clientes="$clientes" />
            <x-dashboard.dashboard-card-04 :compras="$compras" />
            <x-dashboard.dashboard-card-05 :ganancias="$ganancias"  />
            <x-dashboard.dashboard-card-06 :usuario="$usuario"  />







            {{-- <!-- Card (Recent Activity) -->
            <x-dashboard.dashboard-card-12 />

            <!-- Card (Income/Expenses) -->
            <x-dashboard.dashboard-card-13 :ventas="$ventasrealizadas" /> --}}

        </div>

    </div>
</x-app-layout>
