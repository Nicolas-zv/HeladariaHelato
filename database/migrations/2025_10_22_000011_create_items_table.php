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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->boolean('disponible')->default(true);
            $table->decimal('cantidad', 10, 4)->default(0.00);
            $table->decimal('costo_promedio', 10, 4)
                ->nullable()
                ->default(0.00)
                ->comment('Costo promedio ponderado para cálculo de producción.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
