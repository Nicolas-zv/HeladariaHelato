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
        // Usamos el nombre 'valoracions' como lo solicitaste
        Schema::create('valoracions', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique()->comment('Código interno para referencia rápida de la valoración.');

            // Relación con la Nota de Venta (opcional)
            $table->unsignedBigInteger('nota_venta_id')->nullable();
            $table->foreign('nota_venta_id')
                  ->references('id')->on('nota_ventas')
                  ->nullOnDelete()
                  ->cascadeOnUpdate();

            $table->string('nombre_cliente')->comment('Nombre del cliente o "Anónimo".');
            $table->dateTime('fecha_hora');

            // ÚNICO Campo de Calificación (Ratings de 1 a 5)
            $table->unsignedTinyInteger('experiencia_general')->comment('Calificación (1-5) para la experiencia general del servicio.');

            // Comentario: Nullable (no se usa para el CU22)
            // $table->text('comentario')->nullable()->comment('Campo de notas internas o para uso futuro.');

            $table->timestamps();

            $table->index('fecha_hora');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valoracions');
    }
};