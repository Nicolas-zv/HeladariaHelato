<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaCompra extends Model
{
    use HasFactory;

    protected $table = 'nota_compras';

    protected $fillable = [
        'codigo',
        'fecha_hora',
        'total',
        'proveedor_id',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'total' => 'decimal:2',
    ];
/**
     * Una NotaCompra pertenece a un Proveedor (Relación 1:N inversa).
     * Esto está basado en la foreign key 'proveedor_id'.
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * Una NotaCompra tiene muchos ítems de Compra (Relación 1:N).
     * La entidad 'Compra' funciona como la línea de detalle de esta cabecera.
     */
    public function compras()
    {
        return $this->hasMany(Compra::class, 'nota_compra_id');
    }
}