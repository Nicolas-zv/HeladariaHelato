<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaVenta extends Model
{
    use HasFactory;

    // tabla explícita y PK compartida con ordenes.id (composición 1:1)
    protected $table = 'nota_ventas';

    // La PK 'id' no es auto-increment: se reutiliza el id de la orden
    public $incrementing = false;
    protected $keyType = 'int'; // Ajustado a 'int' (o 'integer') para BigInteger
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', // id de la orden (clave primaria compartida)
        'fecha_hora',
        'total',
        'pagado',
        'tipo_pago',
        'cliente_id',
        'empleado_id',
        'mesa_id',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'total' => 'decimal:2',
        'pagado' => 'boolean',
    ];

    /**
     * Relaciones
     */

    // NotaVenta pertenece a la Orden (PK compartida: nota_ventas.id -> ordenes.id)
    public function orden()
    {
        // Se mantiene la relación correcta para PK compartida
        return $this->belongsTo(Orden::class,'id');
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class, 'nota_venta_id');
    }
    
    // Relaciones foráneas
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }
}