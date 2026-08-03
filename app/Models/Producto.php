<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'empresa_id', 'categoria_id', 'codigo', 'codigo_barras', 'nombre',
        'descripcion', 'marca', 'imagen', 'precio_compra', 'precio_venta',
        'impuesto', 'stock', 'stock_minimo', 'unidad',
        'controlar_stock', 'vendible', 'activo',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'impuesto' => 'decimal:2',
        'stock' => 'decimal:2',
        'stock_minimo' => 'decimal:2',
        'controlar_stock' => 'boolean',
        'vendible' => 'boolean',
        'activo' => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'categoria_id');
    }

    public function movimientosStock()
    {
        return $this->hasMany(MovimientoStock::class);
    }

    public function getStockBajoAttribute(): bool
    {
        return $this->controlar_stock && $this->stock <= $this->stock_minimo;
    }

    public function getMargenAttribute(): float
    {
        if ($this->precio_compra <= 0) return 0;
        return round((($this->precio_venta - $this->precio_compra) / $this->precio_compra) * 100, 2);
    }
}
