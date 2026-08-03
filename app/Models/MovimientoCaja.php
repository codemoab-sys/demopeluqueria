<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    use HasFactory;

    protected $table = 'movimientos_caja';

    protected $fillable = [
        'caja_id', 'user_id', 'tipo', 'importe', 'metodo_pago',
        'concepto', 'descripcion', 'referencia', 'fecha',
    ];

    protected $casts = [
        'importe' => 'decimal:2',
        'fecha' => 'datetime',
    ];

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
