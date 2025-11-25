<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenProducto extends Model
{
    use HasFactory;

    protected $table = 'orden_producto';

    protected $fillable = [
        'orden_id',
        'producto_id',
        'cantidad',
        'monto',
        'precio_unitario',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'monto' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
    ];

    public function orden()
    {
        return $this->belongsTo(\App\Models\Orden::class, 'orden_id');
    }

    public function producto()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'producto_id');
    }
}
