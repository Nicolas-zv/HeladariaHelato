<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductoIngrediente extends Pivot
{
    /**
     * Define el nombre de la tabla si no sigue la convención de Laravel.
     * @var string
     */
    protected $table = 'producto_ingrediente';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'producto_id',
        'ingrediente_id',
        'cantidad',
        'unidad', // Aunque es redundante, lo mantenemos por si se asigna desde el form
    ];

    /**
     * Define el tipo de datos para 'cantidad'.
     * @var array
     */
    protected $casts = [
        'cantidad' => 'float',
    ];

    // Opcional: Define las relaciones inversas
    
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function ingrediente()
    {
        return $this->belongsTo(Ingrediente::class);
    }
}