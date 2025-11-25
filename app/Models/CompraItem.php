<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraItem extends Model
{
    use HasFactory;

    protected $table = 'compra_item';

    protected $fillable = [
        'compra_id',
        'item_id',
        'cantidad',
        'costo_unitario',
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}