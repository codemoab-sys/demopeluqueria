<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuraciones';

    protected $fillable = ['empresa_id', 'clave', 'valor', 'grupo', 'tipo', 'descripcion'];

    public static function get(string $clave, $default = null, ?int $empresaId = null)
    {
        $empresaId = $empresaId ?? auth()->user()?->empresa_id ?? Empresa::first()?->id;
        $config = self::where('empresa_id', $empresaId)->where('clave', $clave)->first();
        if (!$config) return $default;

        return match($config->tipo) {
            'integer' => (int) $config->valor,
            'boolean' => (bool) $config->valor,
            'json' => json_decode($config->valor, true),
            default => $config->valor,
        };
    }

    public static function set(string $clave, $valor, string $tipo = 'string', ?int $empresaId = null): void
    {
        $empresaId = $empresaId ?? auth()->user()?->empresa_id ?? Empresa::first()?->id;
        $valorGuardar = $tipo === 'json' ? json_encode($valor) : (string) $valor;
        self::updateOrCreate(
            ['empresa_id' => $empresaId, 'clave' => $clave],
            ['valor' => $valorGuardar, 'tipo' => $tipo]
        );
    }
}
