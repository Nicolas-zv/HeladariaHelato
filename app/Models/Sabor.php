<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sabor extends Model
{
    use HasFactory;

    // Nombre de la tabla tal como la definiste en la migración
    protected $table = 'sabores';

    protected $fillable = [
        'descripcion',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'sabor_id');
    }
}