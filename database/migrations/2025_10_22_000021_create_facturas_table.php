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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id(); // idFactura
            $table->dateTime('fecha_hora')->nullable();
            $table->decimal('monto', 12, 2)->default(0);
            $table->string('codigo_control')->nullable();
            $table->foreignId('nota_venta_id')->nullable()->constrained('nota_ventas')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->index('fecha_hora');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};