<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaSalida extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'cantidad',
        'fecha_hora',
        'item_id',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'cantidad' => 'integer',
    ];
    
    // --- Relaciones ---

    /**
     * La NotaSalida afecta a un Item específico.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    
    // --- Observadores (Para la lógica de Stock) ---
    
    // NOTA: Para producción, es mejor usar un Evento/Listener o un Service.
    // Aquí usamos un método simple para ilustrar la lógica.
    
    /**
     * Reduce el stock del Item asociado al crear la NotaSalida.
     */
    public static function boot()
    {
        parent::boot();

        // Al crear (Ejecuta ANTES de guardar)
        static::creating(function ($salida) {
            if (empty($salida->fecha_hora)) {
                 $salida->fecha_hora = now();
            }
        });

        // Después de crear (Ejecuta DESPUÉS de guardar)
        static::created(function ($salida) {
            $salida->updateItemStock();
        });

        // Al eliminar
        static::deleting(function ($salida) {
            $salida->reverseItemStock();
        });
    }

    /**
     * Lógica para actualizar el stock del Item.
     */
    public function updateItemStock()
    {
        // En una NotaSalida, la cantidad SIEMPRE resta stock
        if ($this->item) {
            $this->item->decrement('cantidad', $this->cantidad);
        }
    }
    
    /**
     * Lógica para revertir el stock si se elimina la NotaSalida.
     */
    public function reverseItemStock()
    {
        // Al eliminar, la cantidad se SUMA de nuevo al stock
        if ($this->item) {
            $this->item->increment('cantidad', $this->cantidad);
        }
    }
}