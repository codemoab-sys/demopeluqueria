<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoBono extends Model
{
    use HasFactory;

    protected $table = 'tipos_bonos';

    protected $fillable = [
        'empresa_id', 'nombre', 'descripcion', 'servicio_id',
        'sesiones', 'precio', 'precio_sesion', 'validez_dias', 'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'precio_sesion' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function bonos()
    {
        return $this->hasMany(Bono::class);
    }
}
