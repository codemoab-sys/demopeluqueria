<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    use HasFactory;

    protected $table = 'backups';

    protected $fillable = ['empresa_id', 'user_id', 'nombre', 'archivo', 'tamano', 'tipo', 'descripcion'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTamanoFormateadoAttribute(): string
    {
        $bytes = $this->tamano;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 2) . ' KB';
        if ($bytes < 1073741824) return round($bytes / 1048576, 2) . ' MB';
        return round($bytes / 1073741824, 2) . ' GB';
    }
}
