<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <h1 class="text-2xl font-semibold mb-4">Editar Empleado</h1>

        <div class="bg-white p-6 rounded shadow">
            <form action="{{ route('empleados.update', $empleado) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium">Persona existente</label>
                    <select name="persona_id" class="w-full px-3 py-2 border rounded">
                        <option value="">— Mantener persona actual / seleccionar otra —</option>
                        @foreach ($personas as $p)
                            <option value="{{ $p->id }}"
                                {{ old('persona_id', $empleado->persona_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->nombre }} {{ $p->carnet ? ' - ' . $p->carnet : '' }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Si dejas vacío, se actualizarán los datos de la persona actual
                        con los campos siguientes.</p>
                </div>

                <hr class="my-4" />

                <div class="grid grid-cols-1 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium">Nombre</label>
                        <input name="nombre" value="{{ old('nombre', $empleado->persona->nombre) }}"
                            class="w-full px-3 py-2 border rounded" />
                        @error('nombre')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Carnet</label>
                            <input name="carnet" value="{{ old('carnet', $empleado->persona->carnet) }}"
                                class="w-full px-3 py-2 border rounded" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Teléfono</label>
                            <input name="telefono" value="{{ old('telefono', $empleado->persona->telefono) }}"
                                class="w-full px-3 py-2 border rounded" />
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Dirección</label>
                    <input name="direccion" value="{{ old('direccion', $empleado->direccion) }}"
                        class="w-full px-3 py-2 border rounded" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Vincular a usuario (opcional)</label>
                    <select name="users_id" class="w-full px-3 py-2 border rounded">
                        <option value="">— Ninguno —</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}"
                                {{ old('users_id', $empleado->users_id) == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded">Actualizar
                        Empleado</button>
                    <a href="{{ route('empleados.index') }}" class="text-gray-600">Volver</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
