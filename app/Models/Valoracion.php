<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la Valoración del Servicio (CU22) con una sola calificación general.
 */
class Valoracion extends Model
{
    // El modelo ahora utiliza la tabla 'valoracions'
    protected $table = 'valoracions';

    protected $fillable = [
        'codigo',
        'nota_venta_id',
        'nombre_cliente',
        'fecha_hora',
        'experiencia_general', // Única calificación requerida
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'experiencia_general' => 'integer',
    ];

    /**
     * Relación con la Nota de Venta asociada.
     */
    public function notaVenta()
    {
        // Asumiendo que existe el modelo NotaVenta
        return $this->belongsTo(NotaVenta::class, 'nota_venta_id');
    }
}