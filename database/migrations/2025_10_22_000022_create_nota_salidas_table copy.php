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
        Schema::create('nota_salidas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->integer('cantidad')->default(0);
            $table->dateTime('fecha_hora')->nullable();
            $table->foreignId('item_id')->nullable()->constrained('items')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->index('codigo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_salidas');
    }
};