<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;


    protected $table = 'clientes';
    protected $primaryKey = 'idcliente';
    public $timestamps = false;
    protected $fillable = ['nombre', 'direccion', 'telefono', 'email', 'estatus'];



 public function ventas(): HasMany
    {
        return $this->hasMany(Venta_Producto::class);
    }


}
