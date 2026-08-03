<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;

    protected $table = 'cajas';

    protected $fillable = [
        'empresa_id', 'user_apertura_id', 'user_cierre_id',
        'fecha_apertura', 'fecha_cierre',
        'importe_inicial', 'importe_final',
        'importe_efectivo', 'importe_tarjeta', 'importe_transferencia', 'importe_otros',
        'total_ventas', 'total_ingresos', 'total_gastos', 'descuadre',
        'estado', 'notas_apertura', 'notas_cierre',
    ];

    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre' => 'datetime',
        'importe_inicial' => 'decimal:2',
        'importe_final' => 'decimal:2',
        'importe_efectivo' => 'decimal:2',
        'importe_tarjeta' => 'decimal:2',
        'importe_transferencia' => 'decimal:2',
        'importe_otros' => 'decimal:2',
        'total_ventas' => 'decimal:2',
        'total_ingresos' => 'decimal:2',
        'total_gastos' => 'decimal:2',
        'descuadre' => 'decimal:2',
    ];

    public function userApertura()
    {
        return $this->belongsTo(User::class, 'user_apertura_id');
    }

    public function userCierre()
    {
        return $this->belongsTo(User::class, 'user_cierre_id');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
