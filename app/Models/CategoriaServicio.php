<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaServicio extends Model
{
    use HasFactory;

    protected $table = 'categorias_servicios';

    protected $fillable = [
        'empresa_id', 'nombre', 'color', 'icono', 'orden', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'categoria_id');
    }
}
