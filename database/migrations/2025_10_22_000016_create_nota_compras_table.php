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
        Schema::create('nota_compras', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->dateTime('fecha_hora')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->index('codigo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_compras');
    }
};