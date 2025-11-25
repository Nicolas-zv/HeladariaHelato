{{-- resources/views/mesas/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mesas
        </h2>
    </x-slot>

    <div x-data="mesasUI()" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alerts --}}
            @if (session('ok'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-green-800 border border-green-200">
                    {{ session('ok') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 p-4 text-red-800 border border-red-200">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Listado</h3>
                    <button @click="openCreate()" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                        + Nueva mesa
                    </button>
                </div>

                {{-- Tabla --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">#</th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">Capacidad</th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">Ubicación</th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">Estado</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($mesas as $m)
                                <tr>
                                    <td class="px-4 py-2 font-medium">Mesa {{ $m->numero }}</td>
                                    <td class="px-4 py-2">{{ $m->capacidad }}</td>
                                    <td class="px-4 py-2">{{ $m->ubicacion ?? '—' }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs
                                            @class([
                                                'bg-green-100 text-green-800' => $m->estado === 'disponible',
                                                'bg-yellow-100 text-yellow-800' => $m->estado === 'reservada',
                                                'bg-red-100 text-red-800' => $m->estado === 'ocupada',
                                                'bg-gray-200 text-gray-800' => $m->estado === 'inactiva',
                                            ])">
                                            {{ ucfirst($m->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-right space-x-1">
                                        <button @click="openShow({{ $m->toJson() }})"
                                                class="px-2 py-1 rounded-md border hover:bg-gray-50">Ver</button>
                                        <button @click="openEdit({{ $m->toJson() }})"
                                                class="px-2 py-1 rounded-md bg-blue-600 text-white hover:bg-blue-700">Editar</button>
                                        <button @click="openDelete({{ $m->toJson() }})"
                                                class="px-2 py-1 rounded-md bg-rose-600 text-white hover:bg-rose-700">Eliminar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        No hay mesas registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="mt-4">
                    {{ $mesas->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL: Crear / Editar --}}
        <div x-show="modalForm.open" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div @click.away="closeForm()"
                 class="w-full max-w-lg bg-white dark:bg-gray-900 rounded-2xl shadow p-6">
                <h3 class="text-lg font-semibold mb-4" x-text="modalForm.title"></h3>

                <form :action="modalForm.action" method="POST">
                    @csrf
                    <template x-if="modalForm.method === 'PUT'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm mb-1">Número</label>
                            <input type="number" name="numero" x-model="form.numero" required min="1"
                                   class="w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Capacidad</label>
                            <input type="number" name="capacidad" x-model="form.capacidad" required min="1" max="20"
                                   class="w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm mb-1">Ubicación</label>
                            <input type="text" name="ubicacion" x-model="form.ubicacion"
                                   class="w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm mb-1">Estado</label>
                            <select name="estado" x-model="form.estado" required
                                    class="w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:border-gray-700">
                                <option value="disponible">Disponible</option>
                                <option value="ocupada">Ocupada</option>
                                <option value="reservada">Reservada</option>
                                <option value="inactiva">Inactiva</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" @click="closeForm()"
                                class="px-4 py-2 rounded-lg border">Cancelar</button>
                        <button type="submit"
                                class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700"
                                x-text="modalForm.submitText"></button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL: Ver --}}
        <div x-show="modalShow.open" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div @click.away="modalShow.open=false"
                 class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Detalles de mesa</h3>
                <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="py-2 flex justify-between">
                        <dt class="text-gray-500">Número</dt>
                        <dd x-text="modalShow.mesa.numero"></dd>
                    </div>
                    <div class="py-2 flex justify-between">
                        <dt class="text-gray-500">Capacidad</dt>
                        <dd x-text="modalShow.mesa.capacidad"></dd>
                    </div>
                    <div class="py-2 flex justify-between">
                        <dt class="text-gray-500">Ubicación</dt>
                        <dd x-text="modalShow.mesa.ubicacion ?? '—'"></dd>
                    </div>
                    <div class="py-2 flex justify-between">
                        <dt class="text-gray-500">Estado</dt>
                        <dd x-text="modalShow.mesa.estado"></dd>
                    </div>
                    <div class="pt-4 text-right">
                        <button @click="modalShow.open=false" class="px-4 py-2 rounded-lg border">Cerrar</button>
                    </div>
                </dl>
            </div>
        </div>

        {{-- MODAL: Eliminar --}}
        <div x-show="modalDelete.open" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div @click.away="modalDelete.open=false"
                 class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Eliminar mesa</h3>
                <p class="mb-6">¿Seguro que deseas eliminar la mesa <span class="font-semibold" x-text="modalDelete.mesa?.numero"></span>?</p>
                <form :action="modalDelete.action" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="modalDelete.open=false" class="px-4 py-2 rounded-lg border">Cancelar</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Alpine.js helpers --}}
    <script>
        function mesasUI() {
            return {
                modalForm: { open: false, title: '', action: '', method: 'POST', submitText: 'Guardar' },
                modalShow: { open: false, mesa: {} },
                modalDelete: { open: false, mesa: null, action: '' },
                form: { numero: 1, capacidad: 2, ubicacion: '', estado: 'disponible' },

                openCreate() {
                    this.modalForm = {
                        open: true,
                        title: 'Nueva mesa',
                        action: '{{ route('mesas.store') }}',
                        method: 'POST',
                        submitText: 'Crear'
                    };
                    this.form = { numero: 1, capacidad: 2, ubicacion: '', estado: 'disponible' };
                },
                openEdit(mesa) {
                    this.modalForm = {
                        open: true,
                        title: 'Editar mesa',
                        action: '{{ url('mesas') }}/' + mesa.id,
                        method: 'PUT',
                        submitText: 'Actualizar'
                    };
                    this.form = {
                        numero: mesa.numero,
                        capacidad: mesa.capacidad,
                        ubicacion: mesa.ubicacion,
                        estado: mesa.estado,
                    };
                },
                closeForm() { this.modalForm.open = false; },

                openShow(mesa) {
                    this.modalShow.mesa = mesa;
                    this.modalShow.open = true;
                },

                openDelete(mesa) {
                    this.modalDelete.mesa = mesa;
                    this.modalDelete.action = '{{ url('mesas') }}/' + mesa.id;
                    this.modalDelete.open = true;
                },
            }
        }
    </script>
</x-app-layout>
