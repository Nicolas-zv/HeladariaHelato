<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Subclase que hereda de Item (via item_id).
 * Representa las materias primas para la producción.
 */
class Ingrediente extends Model
{
    protected $fillable = [
        'item_id', // CLAVE: Llave foránea a la tabla Item
        'unidad',
        'stock', // Cantidad disponible en inventario de la materia prima
    ];

    protected $casts = [
        'stock' => 'float',
    ];

    // --- Relaciones de Herencia y Receta ---

    /**
     * Herencia: Ingrediente pertenece a un Item (Padre).
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * Relación Muchos a Muchos inversa con Producto (es usado en recetas).
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_ingrediente')
                    ->using(ProductoIngrediente::class)
                    ->withPivot('cantidad', 'unidad')
                    ->withTimestamps();
    }
}