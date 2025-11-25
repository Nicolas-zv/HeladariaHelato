<x-app-layout>
    <div class="container mx-auto py-8 px-4">
        @if(session('success'))
            <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Roles</h1>
            <a href="{{ route('roles.create') }}" class="inline-flex items-center rounded-lg px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white">
                Nuevo rol
            </a>
        </div>

        <div class="overflow-hidden rounded-lg shadow bg-white dark:bg-slate-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                            ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                            ROLES
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                @forelse($roles as $role)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $role->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $role->name }}</td>
                        <td class="px-6 py-4 text-sm text-right">
                            <a href="{{ route('roles.edit', $role) }}" class="mr-2 inline-flex items-center rounded px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white">
                                MODIFICAR
                            </a>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este rol?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center rounded px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300" colspan="3">
                            No hay roles.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $roles->links() }}
        </div>
    </div>
</x-app-layout>
