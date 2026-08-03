<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'empresa_id', 'categoria_id', 'codigo', 'nombre', 'descripcion',
        'duracion', 'precio', 'impuesto', 'comision', 'color', 'imagen',
        'reservable_online', 'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'impuesto' => 'decimal:2',
        'comision' => 'decimal:2',
        'reservable_online' => 'boolean',
        'activo' => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaServicio::class, 'categoria_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function getDuracionFormateadaAttribute(): string
    {
        $h = intdiv($this->duracion, 60);
        $m = $this->duracion % 60;
        if ($h > 0 && $m > 0) return "{$h}h {$m}min";
        if ($h > 0) return "{$h}h";
        return "{$m}min";
    }
}
