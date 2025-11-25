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
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_orden')->unique();
            $table->dateTime('fecha_hora')->nullable();
            $table->decimal('sub_total', 12, 2)->default(0);
            $table->foreignId('mesa_id')
                ->constrained('mesas')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            // FK a estado_ordenes
            $table->foreignId('estado_orden_id')
                ->nullable()
                ->constrained('estado_ordenes')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            // FK a clientes
            $table->foreignId('cliente_id')
                ->nullable()
                ->constrained('clientes')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            // FK a empleados
            $table->foreignId('empleado_id')
                ->nullable()
                ->constrained('empleados')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            // Timestamps
            $table->timestamps();

            $table->index('numero_orden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar constraints explícitamente antes de dropear (más seguro en some DB engines)
        Schema::table('ordenes', function (Blueprint $table) {
            // Usamos try/catch para evitar excepciones si no existen
            try {
                $table->dropForeign(['estado_orden_id']);
            } catch (\Throwable $e) {}
            try {
                $table->dropForeign(['cliente_id']);
            } catch (\Throwable $e) {}
            try {
                $table->dropForeign(['empleado_id']);
            } catch (\Throwable $e) {}
        });

        Schema::dropIfExists('ordenes');
    }
};