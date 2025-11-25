<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <h1 class="text-2xl font-semibold mb-4">Crear Empleado</h1>

        <div class="bg-white p-6 rounded shadow">
            <form action="{{ route('empleados.store') }}" method="POST">
                @csrf

                {{-- <p class="text-sm text-gray-600 mb-4">Puedes vincular una Persona existente o crear una nueva rellenando
                    los campos de Persona.</p>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Persona existente</label>
                    <select name="persona_id" class="w-full px-3 py-2 border rounded">
                        <option value="">— Seleccionar persona (opcional) —</option>
                        @foreach ($personas as $p)
                            <option value="{{ $p->id }}" {{ old('persona_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nombre }} {{ $p->carnet ? ' - ' . $p->carnet : '' }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Si seleccionas una persona, los campos de persona abajo se
                        ignorarán.</p>
                </div> --}}

                <hr class="my-4" />

                <div class="grid grid-cols-1 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium">Nombre</label>
                        <input name="nombre" value="{{ old('nombre') }}" class="w-full px-3 py-2 border rounded" />
                        @error('nombre')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Carnet</label>
                            <input name="carnet" value="{{ old('carnet') }}" class="w-full px-3 py-2 border rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Teléfono</label>
                            <input name="telefono" value="{{ old('telefono') }}"
                                class="w-full px-3 py-2 border rounded" />
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Dirección</label>
                    <input name="direccion" value="{{ old('direccion') }}" class="w-full px-3 py-2 border rounded" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Vincular a usuario (opcional)</label>
                    <select name="users_id" class="w-full px-3 py-2 border rounded">
                        <option value="">— Ninguno —</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" {{ old('users_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Guardar Empleado</button>
                    <a href="{{ route('empleados.index') }}" class="text-gray-600">Volver</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
