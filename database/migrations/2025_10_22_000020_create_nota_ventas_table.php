<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nota_ventas', function (Blueprint $table) {
            // la columna 'id' no es auto-increment: será igual al id de la orden
            $table->unsignedBigInteger('id')->primary();
            $table->foreign('id')->references('id')->on('ordenes')->onDelete('cascade')->onUpdate('cascade');

            $table->dateTime('fecha_hora')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->boolean('pagado')->default(false);
            $table->string('tipo_pago')->nullable();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('mesa_id')->nullable()->constrained('mesas')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->index('fecha_hora');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nota_ventas');
    }
};