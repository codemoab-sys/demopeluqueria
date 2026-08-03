<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bono extends Model
{
    use HasFactory;

    protected $table = 'bonos';

    protected $fillable = [
        'empresa_id', 'cliente_id', 'tipo_bono_id', 'servicio_id',
        'codigo', 'sesiones_total', 'sesiones_usadas', 'precio',
        'fecha_compra', 'fecha_caducidad', 'estado', 'notas',
    ];

    protected $casts = [
        'fecha_compra' => 'date',
        'fecha_caducidad' => 'date',
        'precio' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function tipoBono()
    {
        return $this->belongsTo(TipoBono::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function usos()
    {
        return $this->hasMany(UsoBono::class);
    }

    public function getSesionesRestantesAttribute(): int
    {
        return $this->sesiones_total - $this->sesiones_usadas;
    }

    public function getPorcentajeUsadoAttribute(): float
    {
        if ($this->sesiones_total == 0) return 0;
        return round(($this->sesiones_usadas / $this->sesiones_total) * 100, 1);
    }
}
