<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $fillable = [
        'nombre', 'cif', 'direccion', 'ciudad', 'codigo_postal', 'provincia', 'pais',
        'telefono', 'email', 'web', 'logo',
        'simbolo_moneda', 'codigo_moneda', 'impuesto_default',
        'zona_horaria', 'idioma', 'formato_fecha',
        'hora_apertura', 'hora_cierre', 'dias_laborables', 'intervalo_citas',
        'mensaje_ticket', 'activo',
    ];

    protected $casts = [
        'dias_laborables' => 'array',
        'activo' => 'boolean',
        'impuesto_default' => 'decimal:2',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function configuraciones()
    {
        return $this->hasMany(Configuracion::class);
    }

    public function logoUrl(): ?string
    {
        if (!$this->logo) {
            return null;
        }
        return asset('storage/' . $this->logo);
    }
}
