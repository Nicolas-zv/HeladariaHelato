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
        Schema::create('compras', function (Blueprint $table) {
            $table->id(); // nroCompra
            $table->string('nro_compra')->unique();
            $table->integer('cantidad')->default(0);
            $table->decimal('costo', 12, 2)->default(0);
           $table->foreignId('nota_compra_id')->nullable()->constrained('nota_compras')->onDelete('cascade')->cascadeOnUpdate();
            $table->timestamps();

            $table->index('nro_compra');
            $table->foreignId('item_id')->constrained('items')->onDelete('restrict')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};