<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ingredientes', function (Blueprint $table) {
            $table->id();

            // Campos específicos de Ingrediente
            $table->string('unidad')->nullable(); // e.g., gr, kg, ml, unidad
            $table->decimal('stock', 12, 3)->default(0); // cantidad disponible en inventario
            
            // CLAVE DE HERENCIA (MTI): Enlaza a la tabla 'items'
            // Debe ser UNIQUE para asegurar que un item es un producto o un ingrediente.
            $table->foreignId('item_id')->unique()->constrained('items')->cascadeOnDelete()->cascadeOnUpdate(); 

            $table->timestamps();
            
            // NOTA: Eliminamos $table->string('nombre')->unique() ya que 'nombre' y 'codigo' están en 'items'.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredientes');
    }
};