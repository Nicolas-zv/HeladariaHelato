<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Clase Padre (Superclase) para Producto e Ingrediente.
 * Contiene datos de inventario y código/nombre.
 */
class Item extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'disponible',
        'cantidad', // Stock físico general (unidades vendibles)
        'costo_promedio',
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'cantidad' => 'decimal:4',
         'costo_promedio' => 'decimal:4',
    ];

    /**
     * Relación de subclase: Un Item puede ser un Producto (Relación de Herencia).
     */
    public function producto()
    {
        return $this->hasOne(Producto::class);
    }
    
    /**
     * Relación de subclase: Un Item puede ser un Ingrediente (Relación de Herencia).
     */
    public function ingrediente()
    {
        return $this->hasOne(Ingrediente::class);
    }
    public function actualizarCostoPromedio(float $cantidadComprada, float $costoUnitarioNuevo)
    {
        // Si no se compra nada, no hay que actualizar el costo
        if ($cantidadComprada <= 0) {
            return;
        }

        // El stock actual (antes de la compra) y el costo promedio anterior
        // Usamos el valor de la base de datos antes de la transacción de compra.
        $stockAnterior = $this->getOriginal('cantidad') ?? 0; 
        $costoUnitarioAnterior = $this->costo_promedio ?? 0;
        
        // Costo total de la nueva compra
        $costoTotalCompra = $cantidadComprada * $costoUnitarioNuevo;

        // Valor total del inventario antes de la compra
        $valorTotalAnterior = $stockAnterior * $costoUnitarioAnterior;
        $valorTotalNuevo = $valorTotalAnterior + $costoTotalCompra;
        // Nuevo stock total
        $stockNuevo = $stockAnterior + $cantidadComprada;
        
        $nuevoCostoPromedio = 0;

        if ($stockNuevo > 0) {
            // Fórmula del Costo Promedio Ponderado
            $nuevoCostoPromedio = $valorTotalNuevo / $stockNuevo;
        } else {
            // Esto solo se aplica si el stock anterior era 0.
            $nuevoCostoPromedio = $costoUnitarioNuevo; 
        }
        // Guardar el nuevo costo promedio redondeado
        $this->costo_promedio = round($nuevoCostoPromedio, 4); 
        $this->save();
        
    }
}