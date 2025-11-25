<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ⭐ CORRECCIÓN CLAVE: Usamos 'collect()' para asegurar que map() siempre funcione.
        // Si $items no existe o es null, usamos una colección vacía.
        const items = @json(collect($items ?? [])->map(function($i){ 
            return ['id' => $i->id, 'nombre' => $i->nombre, 'codigo' => $i->codigo]; 
        }));
        
        // El old('detalles') ya está blindado con $detalles_data ?? []
        const existingDetalles = @json(old('detalles', $detalles_data ?? []));

        const container = document.getElementById('itemsContainer');
        const tpl = document.getElementById('itemRowTemplate');
        const addRowBtn = document.getElementById('addRowBtn');
        const subTotalDisplay = document.getElementById('subTotalDisplay');
        
        let rowCounter = 0; 
        
        // ... (el resto de tus funciones: escapeHtml, createSelectOptions, recalcLine, etc.)

        // Añade una nueva fila al formulario
        function addRow(prefill = {}) {
            // ⭐ AÑADIR VERIFICACIÓN DE TEMPLATE: Si el template es null, salimos.
            if (!tpl) {
                console.error("Template 'itemRowTemplate' no encontrado. Script abortado.");
                return;
            }
            const clone = tpl.content.cloneNode(true);
            const row = clone.querySelector('.item-row');
            // ... (el resto de la función addRow)
        }

        // ⭐ Event Listener para el botón (Asegurarse de que el botón existe)
        if (addRowBtn) {
             addRowBtn.addEventListener('click', function(e) { 
                 e.preventDefault(); 
                 addRow(); 
             });
        } else {
             console.error("Botón 'addRowBtn' no encontrado. La función de agregar no está activa.");
        }
        
        // ⭐ Inicialización: Cargar detalles existentes o agregar uno vacío
        if (Array.isArray(existingDetalles) && existingDetalles.length > 0) {
            existingDetalles.forEach(function(e) { addRow(e); });
        } else if (container && tpl) {
            // Añade una fila vacía solo si el contenedor y el template existen
            addRow(); 
        }
    });
</script>