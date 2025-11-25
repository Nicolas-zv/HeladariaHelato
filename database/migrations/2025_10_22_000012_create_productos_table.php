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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            
            // Campos específicos de Producto
            $table->decimal('precio', 10, 2)->default(0);
            $table->text('descripcion')->nullable();
            
            // Llaves foráneas a otras tablas
            $table->foreignId('tipo_id')->nullable()->constrained('tipos')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('sabor_id')->nullable()->constrained('sabores')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('menu_id')->nullable()->constrained('menus')->nullOnDelete()->cascadeOnUpdate();
            
            // Debe ser UNIQUE para asegurar que un item es un producto o un ingrediente, no ambos.
            $table->foreignId('item_id')->unique()->constrained('items')->cascadeOnDelete()->cascadeOnUpdate(); 

            $table->timestamps();
                     
            // Si quieres mantener cierta unicidad:
            // $table->unique('item_id'); // Ya está implícito arriba
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};