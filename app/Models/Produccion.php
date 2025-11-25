<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'cantidad_producida',
        'costo_total_ingredientes', // Costo de los ingredientes consumidos
        'fecha_produccion',
        'user_id', // Quién registró la producción
    ];

    protected $casts = [
        'fecha_produccion' => 'datetime',
    ];

    // Relación: Un registro de producción pertenece a un Producto (el producto final)
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Relación: Un registro de producción fue creado por un Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}