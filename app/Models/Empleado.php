<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'persona_id',
        'direccion',
        'users_id', // <-- ajustado al nombre de columna en la migración
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function usuario()
    {
        // columna users_id en la tabla
        return $this->belongsTo(User::class, 'users_id');
    }

    public function ordenes()
    {
        return $this->hasMany(Orden::class, 'empleado_id');
    }

    public function notaVentas()
    {
        return $this->hasMany(NotaVenta::class, 'empleado_id');
    }
}
