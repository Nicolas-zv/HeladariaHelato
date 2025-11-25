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
        Schema::create('produccions', function (Blueprint $table) {
            $table->id();

            // Clave foránea al producto que se está creando
            $table->foreignId('producto_id')->constrained('productos');
            
            // Cantidad de unidades del producto final que se produjeron
            $table->decimal('cantidad_producida', 10, 3);
            
            // El costo total de los ingredientes consumidos para este lote.
            $table->decimal('costo_total_ingredientes', 10, 2); 
            
            // Fecha y hora en que se registró la producción
            $table->dateTime('fecha_produccion');

            // Quién registró la producción
            $table->foreignId('user_id')->nullable()->constrained('users');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produccions');
    }
};