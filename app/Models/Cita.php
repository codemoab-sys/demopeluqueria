<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'empresa_id', 'cliente_id', 'empleado_id',
        'fecha', 'hora_inicio', 'hora_fin', 'duracion_total', 'precio_total',
        'estado', 'color', 'notas', 'recordatorio_enviado', 'origen',
    ];

    protected $casts = [
        'fecha' => 'date',
        'precio_total' => 'decimal:2',
        'recordatorio_enviado' => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function servicios()
    {
        return $this->hasMany(CitaServicio::class);
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'pendiente' => 'warning',
            'confirmada' => 'info',
            'en_curso' => 'primary',
            'finalizada' => 'success',
            'cancelada' => 'secondary',
            'no_asistio' => 'danger',
            default => 'secondary',
        };
    }
}
