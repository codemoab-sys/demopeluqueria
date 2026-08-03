<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'empresa_id', 'name', 'email', 'password', 'telefono',
        'rol', 'avatar', 'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function empleado()
    {
        return $this->hasOne(Empleado::class);
    }

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function isGerente(): bool
    {
        return in_array($this->rol, ['admin', 'gerente']);
    }
}
