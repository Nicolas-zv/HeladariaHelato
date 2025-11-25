<div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-xl">
    
    {{-- Manejo de Errores de Sesión (opcional, si no usas un layout que lo maneje) --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p class="font-bold">Por favor, corrige los siguientes errores:</p>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ $action }}" method="POST">
        @csrf
        @method($method) {{-- Será 'POST' en create y 'PUT'/'PATCH' en edit --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            
            {{-- Código --}}
            <div>
                <label for="codigo" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Código</label>
                <input type="text" name="codigo" id="codigo" 
                       value="{{ old('codigo', $item->codigo ?? '') }}" 
                       required
                       class="mt-1 block w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('codigo') border-red-500 @enderror dark:bg-slate-700 dark:border-slate-600 dark:text-white" />
                @error('codigo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Nombre --}}
            <div>
                <label for="nombre" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nombre</label>
                <input type="text" name="nombre" id="nombre" 
                       value="{{ old('nombre', $item->nombre ?? '') }}" 
                       required
                       class="mt-1 block w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('nombre') border-red-500 @enderror dark:bg-slate-700 dark:border-slate-600 dark:text-white" />
                @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            {{-- Cantidad (Stock Físico) --}}
            <div>
                <label for="cantidad" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Cantidad (Stock Físico)</label>
                <input type="number" step="1" min="0" name="cantidad" id="cantidad" 
                       value="{{ old('cantidad', $item->cantidad ?? 0) }}" 
                       required
                       class="mt-1 block w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('cantidad') border-red-500 @enderror dark:bg-slate-700 dark:border-slate-600 dark:text-white" />
                @error('cantidad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            {{-- Producto ID (Relación) --}}
            <div>
                <label for="producto_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Producto Asociado (Opcional)</label>
                <select name="producto_id" id="producto_id" 
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                    <option value="">— Sin producto asociado —</option>
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id }}" 
                                {{ (old('producto_id', $item->producto_id ?? '') == $producto->id) ? 'selected' : '' }}>
                            {{ $producto->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('producto_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div> {{-- Fin grid --}}

        {{-- Disponible (Checkbox) --}}
        <div class="mb-6 border-t pt-4 border-slate-200 dark:border-slate-700">
            <div class="flex items-center">
                <input id="disponible" name="disponible" type="checkbox" value="1" 
                       {{ old('disponible', $item->disponible ?? true) ? 'checked' : '' }} 
                       class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:checked:bg-indigo-600" />
                <label for="disponible" class="ml-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Ítem disponible para uso/venta
                </label>
            </div>
            @error('disponible') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
            <a href="{{ route('items.index') }}"
                class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600 dark:hover:bg-slate-600">
                Cancelar
            </a>
            <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ $method === 'POST' ? 'Crear Ítem' : 'Actualizar Ítem' }}
            </button>
        </div>
    </form>
</div>