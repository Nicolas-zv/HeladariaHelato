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
        // 
        Schema::create('compra_item', function (Blueprint $table) {
            $table->id();
//            $table->foreignId('compra_id')->constrained('compras')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('cantidad')->default(1);
            $table->decimal('costo_unitario', 12, 2)->default(0);
            $table->timestamps();

 //           $table->unique(['compra_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compra_item');
    }
};