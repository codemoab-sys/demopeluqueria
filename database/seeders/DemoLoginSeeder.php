<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoLoginSeeder extends Seeder
{
    public function run(): void
    {
        $empresa = Empresa::firstOrCreate(['id' => 1], [
            'nombre' => 'Salón de Belleza Glow',
        ]);

        User::updateOrCreate(
            ['email' => 'demo@demo.com'],
            [
                'name' => 'demo',
                'password' => 'demo@demo.com',
                'rol' => 'admin',
                'empresa_id' => $empresa->id,
                'activo' => true,
            ]
        );
    }
}
