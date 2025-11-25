@php
    // Usamos el operador ?? para garantizar que exista y evitar errores si no se pasa.
    $receta_data = $receta_data ?? []; 
    // Contamos el número de líneas existentes para empezar el índice JS
    $initialCount = count(old('receta', $receta_data));
@endphp

@push('scripts')
<script>
    // Inicializa el contador basado en el número de líneas existentes (para continuar la indexación)
    let lineCounter = {{ $initialCount > 0 ? $initialCount : 1 }};
    
    // Generamos las opciones del select dinámicamente con Blade UNA SOLA VEZ
    let itemOptions = `<option value="">-- Seleccione Ingrediente --</option>`;
    @foreach ($ingredientes as $ingrediente)
        // Guardamos la unidad en el atributo data-unidad del option
        itemOptions += `<option value="{{ $ingrediente->id }}" data-unidad="{{ $ingrediente->unidad }}">{{ $ingrediente->item->nombre }}</option>`;
    @endforeach
    
    // Función para obtener la plantilla HTML de una nueva línea
    function getNewLineTemplate(index) {
        return `
            <div class="receta-line grid grid-cols-12 gap-2 items-center">
                <div class="col-span-6">
                    <select name="receta[${index}][ingrediente_id]" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 select-ingrediente">
                        ${itemOptions}
                    </select>
                </div>
                <div class="col-span-4">
                    <input type="number" name="receta[${index}][cantidad]" step="0.001" min="0.001" placeholder="Cantidad" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 input-cantidad">
                </div>
                <div class="col-span-1 text-center text-sm text-gray-600 unidad-label">
                </div>
                <div class="col-span-1 flex justify-end">
                    <button type="button" class="remove-receta-line text-red-600 hover:text-red-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </div>
        `;
    }

    // Actualiza la etiqueta de unidad basada en la selección
    function updateUnitLabel(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const unitLabel = selectElement.closest('.receta-line').querySelector('.unidad-label');
        
        if (selectedOption && unitLabel) {
            unitLabel.textContent = selectedOption.dataset.unidad || '';
        }
    }

    // Funciones de Eventos
    function bindEvents(container) {
        // Eventos de remoción
        container.querySelectorAll('.remove-receta-line').forEach(button => {
            button.onclick = function() {
                this.closest('.receta-line').remove();
            };
        });

        // Eventos de cambio en el select para actualizar la unidad visualmente
        container.querySelectorAll('.select-ingrediente').forEach(select => {
            select.addEventListener('change', function() {
                updateUnitLabel(this);
            });
            // Ejecutar una vez al cargar para las líneas existentes
            updateUnitLabel(select);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const addLineButton = document.getElementById('add-receta-line');
        const container = document.getElementById('receta-container');
        
        if (addLineButton) {
            addLineButton.addEventListener('click', function() {
                // Agregar la nueva línea al DOM
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = getNewLineTemplate(lineCounter).trim();
                const newLine = tempDiv.firstChild;

                if (newLine) {
                    container.appendChild(newLine);
                    lineCounter++;
                    bindEvents(newLine); // Bindear solo a la nueva línea
                }
            });
        }

        // Inicializar eventos de eliminación y unidad para TODAS las líneas (incluyendo las cargadas por Blade)
        bindEvents(container);
    });
</script>
@endpush    