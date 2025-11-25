<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'nro_compra', 
        'cantidad',
        'costo',
        'nota_compra_id',
        'item_id',
    ];


    // Una compra pertenece a una NotaCompra (Cabecera)
    public function notaCompra()
    {
        return $this->belongsTo(NotaCompra::class, 'nota_compra_id');
    }

    // Una compra pertenece a un Item (Producto)
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}