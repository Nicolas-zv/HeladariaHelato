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
        Schema::create('producto_ingrediente', function (Blueprint $table) {
            $table->id();
            
            // Llaves foráneas a las entidades principales
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('ingrediente_id')->constrained('ingredientes')->cascadeOnDelete()->cascadeOnUpdate();
            
            // Campos adicionales de la relación (la receta)
            $table->decimal('cantidad', 12, 3)->default(0); // cantidad de ese ingrediente por producto
            $table->string('unidad')->nullable(); // (redundante, pero útil para la receta)
            
            $table->timestamps();

            // Restricción: No puede haber el mismo ingrediente dos veces en la receta del mismo producto
            $table->unique(['producto_id', 'ingrediente_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_ingrediente');
    }
};