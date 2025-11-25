<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoOrden extends Model
{
    use HasFactory;

    protected $table = 'estado_ordenes';

    protected $fillable = [
        'nombre',
    ];

    public function ordenes()
    {
        return $this->hasMany(Orden::class, 'estado_orden_id');
    }
}