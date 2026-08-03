<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaServicio extends Model
{
    use HasFactory;

    protected $table = 'cita_servicios';

    protected $fillable = [
        'cita_id', 'servicio_id', 'empleado_id',
        'duracion', 'precio', 'descuento', 'orden',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'descuento' => 'decimal:2',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
