<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'persona_id',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function ordenes()
    {
        return $this->hasMany(Orden::class, 'cliente_id');
    }

    public function notaVentas()
    {
        return $this->hasMany(NotaVenta::class, 'cliente_id');
    }
}