<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class Detalle_venta_producto extends Model
{
    use HasFactory;

    protected $table='detalle_ventas';

    protected $primaryKey='iddetalle_venta';

    public $timestamps= false;

    protected $fillable = [
        "venta_id",
        "articulo_id",
        "apertura_id",
        "cantidad",
        "precio_venta",
        "descuento",
        "subtotal"
    ];

 /**
     * Un detalle de venta pertenece a una venta.
     */
    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta_Producto::class);
    }


    /**
     * Un detalle de venta se refiere a un producto.
     * (Descomenta y ajusta si tienes un modelo Producto)
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Articulo::class); 
    }

       /**
     * Accesor para el subtotal de la línea de detalle.
     * Útil si no tienes un campo 'subtotal' precalculado.
     */
    public function getSubtotalCalculadoAttribute(): float
    {
        return ($this->cantidad ?? 0) * ($this->pventa ?? 0);
    }


}
