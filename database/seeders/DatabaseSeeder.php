<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Empresa por defecto
        $empresa = Empresa::firstOrCreate(['id' => 1], [
            'nombre' => 'Salón de Belleza Glow',
            'cif' => '20600000001',
            'direccion' => 'Av. Principal 123',
            'ciudad' => 'Lima',
            'codigo_postal' => '15001',
            'provincia' => 'Lima',
            'pais' => 'Perú',
            'telefono' => '+51 999 123 456',
            'email' => 'hola@glowsalon.pe',
            'simbolo_moneda' => 'S/.',
            'codigo_moneda' => 'PEN',
            'impuesto_default' => 18.00,
            'zona_horaria' => 'America/Lima',
            'idioma' => 'es',
            'formato_fecha' => 'd/m/Y',
            'hora_apertura' => '09:00',
            'hora_cierre' => '20:00',
            'dias_laborables' => ['lun', 'mar', 'mie', 'jue', 'vie', 'sab'],
            'intervalo_citas' => 15,
            'mensaje_ticket' => '¡Gracias por tu visita! Te esperamos pronto.',
            'activo' => true,
        ]);

        // Usuario admin
        User::firstOrCreate(['email' => 'admin@tpv.com'], [
            'name' => 'Administrador',
            'password' => Hash::make('admin1234'),
            'rol' => 'admin',
            'empresa_id' => $empresa->id,
            'activo' => true,
        ]);

        // Usuario empleado de ejemplo
        User::firstOrCreate(['email' => 'empleado@tpv.com'], [
            'name' => 'María García',
            'password' => Hash::make('empleado1234'),
            'rol' => 'empleado',
            'empresa_id' => $empresa->id,
            'activo' => true,
        ]);

        $this->call([
            DemoLoginSeeder::class,
            CatalogoSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
