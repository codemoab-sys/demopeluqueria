<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'empresa_id', 'codigo', 'nombre', 'apellidos', 'dni', 'telefono', 'email',
        'fecha_nacimiento', 'genero', 'direccion', 'ciudad', 'codigo_postal',
        'foto', 'notas', 'alergias', 'preferencias',
        'puntos_fidelidad', 'saldo', 'acepta_marketing', 'acepta_rgpd',
        'fecha_alta', 'ultima_visita', 'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_alta' => 'date',
        'ultima_visita' => 'date',
        'acepta_marketing' => 'boolean',
        'acepta_rgpd' => 'boolean',
        'activo' => 'boolean',
        'saldo' => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function bonos()
    {
        return $this->hasMany(Bono::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombre . ' ' . ($this->apellidos ?? ''));
    }

    public function getEdadAttribute(): ?int
    {
        return $this->fecha_nacimiento?->age;
    }
}
