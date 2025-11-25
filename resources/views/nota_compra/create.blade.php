<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">➕ Crear Nueva Nota de Compra</h1>

        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl">
            {{-- NOTA: Asumo que tu ruta es 'nota_compras.store' y no 'nota-compra.store' --}}
            <form action="{{ route('nota-compra.store') }}" method="POST"> 
                @csrf
                
                {{-- 
                    INCLUYE EL FORMULARIO REUSABLE. 
                    El nombre del parcial debería ser `_form` o `form`. Mantendré `form`.
                --}}
                @include('nota_compra._form', ['notaCompra' => new \App\Models\NotaCompra(), 'proveedores' => $proveedores, 'items' => $items])

                <div class="mt-8 pt-4 border-t dark:border-gray-700 flex justify-end">
                    <a href="{{ route('nota-compra.index') }}" class="px-4 py-2 mr-3 text-gray-700 dark:text-gray-300 border rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition shadow-md">
                        Guardar Nota de Compra
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script para añadir filas dinámicas (¡Importante!) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('compras-container');
            const addButton = document.getElementById('add-compra');
            let compraIndex = 0; // **INICIAMOS EN 0**

            // Genera el HTML de la fila base (con PLACEHOLDER para el índice)
            const rowTemplate = `
                @php 
                    // Renderiza la plantilla del parcial una sola vez.
                    // Usamos un nombre de variable que no entre en conflicto con PHP real: INDEX_PLACEHOLDER
                    echo view('nota_compra.partials.compra_row', [
                        'index' => 'INDEX_PLACEHOLDER', 
                        'items' => $items,
                    ])->render();
                @endphp
            `;
            
            // 1. Inicialización: Aseguramos que haya al menos una fila al cargar
            if (container && container.children.length === 0) {
                const initialRowHtml = rowTemplate.replace(/INDEX_PLACEHOLDER/g, compraIndex);
                container.insertAdjacentHTML('beforeend', initialRowHtml);
                compraIndex++;
            }

            // 2. Manejador para añadir filas
            if (addButton) {
                addButton.addEventListener('click', function() {
                    const newRowHtml = rowTemplate.replace(/INDEX_PLACEHOLDER/g, compraIndex);
                    container.insertAdjacentHTML('beforeend', newRowHtml);
                    compraIndex++;
                });
            }

            // 3. Manejador para eliminar filas (Delegación de eventos)
            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-compra')) {
                    const rowToRemove = e.target.closest('.item-compra');
                    
                    // Solo permitir eliminar si quedan más de un ítem
                    if (container.children.length > 1) {
                         rowToRemove.remove();
                    } else {
                        alert('Una Nota de Compra debe tener al menos un ítem.');
                    }
                }
            });
        });
    </script>
</x-app-layout>