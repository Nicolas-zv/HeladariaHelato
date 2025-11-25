<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Subclase que hereda de Item (via item_id).
 * Representa los artículos finales vendibles.
 */
class Producto extends Model
{
    protected $fillable = [
        'item_id', // CLAVE: Llave foránea a la tabla Item
        'precio',
        'tipo_id',
        'sabor_id',
        'menu_id',
        'descripcion',
    ];

    protected $casts = [
        'precio' => 'float',
    ];

    // --- Relaciones de Herencia y Receta ---

    /**
     * Herencia: Producto pertenece a un Item (Padre).
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * Relación Muchos a Muchos con Ingrediente (La Receta).
     */
    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class, 'producto_ingrediente')
                    ->using(ProductoIngrediente::class) // Usar el modelo Pivote
                    ->withPivot('cantidad', 'unidad') // Campos extra en la tabla pivote
                    ->withTimestamps();
    }
    
    // --- Relaciones de Clasificación ---
    
    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }

    public function sabor()
    {
        return $this->belongsTo(Sabor::class,'item_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}