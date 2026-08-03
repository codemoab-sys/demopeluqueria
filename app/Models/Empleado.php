<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = [
        'empresa_id', 'user_id', 'nombre', 'apellidos', 'dni', 'telefono', 'email',
        'cargo', 'color', 'foto', 'comision', 'salario',
        'fecha_alta', 'fecha_baja', 'horario', 'notas', 'activo',
    ];

    protected $casts = [
        'horario' => 'array',
        'fecha_alta' => 'date',
        'fecha_baja' => 'date',
        'activo' => 'boolean',
        'comision' => 'decimal:2',
        'salario' => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombre . ' ' . ($this->apellidos ?? ''));
    }
}
