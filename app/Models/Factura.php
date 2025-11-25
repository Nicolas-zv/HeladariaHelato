<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_hora',
        'monto',
        'codigo_control',
        'nota_venta_id',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    public function notaVenta()
    {
        return $this->belongsTo(NotaVenta::class, 'nota_venta_id');
    }
}