<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoVenta extends Model
{
    use HasFactory;

    protected $table = 'pagos_ventas';

    protected $fillable = ['venta_id', 'metodo', 'importe', 'referencia', 'fecha'];

    protected $casts = [
        'importe' => 'decimal:2',
        'fecha' => 'datetime',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
