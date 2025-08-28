<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta_producto extends Model
{
     use HasFactory;

    protected $table='ventas';

    protected $primaryKey='idventa';

    public $timestamps= false;

    protected $fillable = [
        "user_id",
        "cliente_id",
        "tipo_comprobante",
        "num_folio",
        "fecha_hora",
        "efectivo",
        "total_venta",
        "estado"
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

     /**
     * Una venta tiene muchos detalles (productos).
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(Detalle_venta_producto::class);
    }

 public function getTotalCalculadoAttribute(): float
    {
        return $this->detalles->sum(function ($detalle) {
            // Asegúrate que los campos 'cantidad' y 'precio_unitario' existan en DetalleVenta
            return ($detalle->cantidad ?? 0) * ($detalle->precio_venta?? 0);
        });
    }
}




