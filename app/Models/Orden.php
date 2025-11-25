<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;

    protected $table = 'ordenes';

    protected $fillable = [
        'numero_orden',
        'fecha_hora',
        'sub_total',        // Corresponde al total calculado en el formulario
        'estado_orden_id',
        'cliente_id',
        'empleado_id',
        'mesa_id',          // ⭐ CRUCIAL: Añadido, ya que es requerido por el formulario
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'sub_total' => 'decimal:2',
    ];

    // Relación: Orden tiene muchos detalles (OrdenProducto)
    public function detalles()
    {
        // Renombré la función a 'detalles()' por convención para que coincida con el controlador
        return $this->hasMany(\App\Models\OrdenProducto::class, 'orden_id');
    }

    public function productos()
    {
        return $this->belongsToMany(
            \App\Models\Producto::class,
            'orden_producto',
            'orden_id',
            'producto_id'
        )->withPivot(['cantidad', 'monto', 'precio_unitario'])
         ->withTimestamps();
    }

    public function estado()
    {
        return $this->belongsTo(\App\Models\EstadoOrden::class, 'estado_orden_id');
    }

    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'cliente_id');
    }

    public function empleado()
    {
        return $this->belongsTo(\App\Models\Empleado::class, 'empleado_id');
    }
    
    public function mesa()
    {
        return $this->belongsTo(\App\Models\Mesa::class, 'mesa_id');
    }
}