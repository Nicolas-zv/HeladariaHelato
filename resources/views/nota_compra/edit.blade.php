<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">✏️ Editar Nota de Compra: {{ $notaCompra->codigo }}</h1>

        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl">
            <form action="{{ route('nota-compra.update', $notaCompra) }}" method="POST">
                @csrf @method('PUT')
                
                {{-- Incluye el formulario reusable --}}
                @include('nota_compra._form', ['notaCompra' => $notaCompra, 'proveedores' => $proveedores, 'items' => $items])
                <div class="mt-8 pt-4 border-t dark:border-gray-700 flex justify-end">
                    <a href="{{ route('nota-compra.index') }}" class="px-4 py-2 mr-3 text-gray-700 dark:text-gray-300 border rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition shadow-md">
                        Actualizar Nota de Compra
                    </button>
                </div>
            </form>
            
            {{-- Aviso sobre la Edición de Compras --}}
            <div class="mt-4 p-3 bg-yellow-100 text-yellow-800 rounded-md dark:bg-yellow-800 dark:text-yellow-100">
                 Nota: Para modificar los ítems, cantidad o costo, se recomienda **eliminar y volver a crear la nota** o implementar un sistema de gestión de inventario avanzado. La actualización directa de items no está disponible en este formulario de edición simple para evitar problemas de stock.
            </div>
        </div>
    </div>
</x-app-layout>