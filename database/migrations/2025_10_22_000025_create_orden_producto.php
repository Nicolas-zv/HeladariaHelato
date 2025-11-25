<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Esta migración crea la tabla 'orden_producto' SIN índice UNIQUE en (orden_id, producto_id),
     * de modo que una orden pueda tener múltiples filas (items) incluso con el mismo producto.
     *
     * IMPORTANTE: Asegurate de que las migraciones que crean las tablas 'ordenes' y 'productos'
     * tengan un timestamp anterior a esta migración (es decir, se ejecuten antes) o que existan
     * cuando ejecutes `php artisan migrate` / `migrate:fresh`.
     */
    public function up(): void
    {
        // Si por algún motivo ya existe la tabla (no debería en migrate:fresh), la ignoramos.
        if (Schema::hasTable('orden_producto')) {
            return;
        }

        Schema::create('orden_producto', function (Blueprint $table) {
            $table->id();

            // FK a ordenes
            $table->foreignId('orden_id')
                ->constrained('ordenes')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // FK a productos
            $table->foreignId('producto_id')
                ->constrained('productos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // Campos de la línea (item)
            $table->integer('cantidad')->unsigned()->default(1);
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->decimal('monto', 12, 2)->default(0);

            $table->timestamps();

            // índices simples para performance (no UNIQUE)
            $table->index('orden_id');
            $table->index('producto_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Antes de dropear la tabla, intentamos eliminar las FK/índices (por compatibilidad entre motores)
        if (Schema::hasTable('orden_producto')) {
            Schema::table('orden_producto', function (Blueprint $table) {
                try {
                    $table->dropForeign(['orden_id']);
                } catch (\Throwable $e) {}
                try {
                    $table->dropForeign(['producto_id']);
                } catch (\Throwable $e) {}
                try {
                    $table->dropIndex(['orden_id']);
                } catch (\Throwable $e) {}
                try {
                    $table->dropIndex(['producto_id']);
                } catch (\Throwable $e) {}
            });

            Schema::dropIfExists('orden_producto');
        }
    }
};
