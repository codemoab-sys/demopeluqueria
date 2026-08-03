<?php

namespace Database\Seeders;

use App\Models\Bono;
use App\Models\Caja;
use App\Models\Cita;
use App\Models\CitaServicio;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Empleado;
use App\Models\MovimientoCaja;
use App\Models\MovimientoStock;
use App\Models\PagoVenta;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\TipoBono;
use App\Models\UsoBono;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $empresaId = 1;
        $userId = 1; // admin

        // ============== EMPLEADOS (10 totales) ==============
        $empleadosExtra = [
            ['nombre' => 'Sofía',   'apellidos' => 'Ruiz',     'cargo' => 'Estilista',    'color' => '#10b981', 'comision' => 11, 'telefono' => '612345001'],
            ['nombre' => 'Pablo',   'apellidos' => 'Sánchez',  'cargo' => 'Barbero',      'color' => '#8b5cf6', 'comision' => 9,  'telefono' => '612345002'],
            ['nombre' => 'Lucía',   'apellidos' => 'Fernández','cargo' => 'Colorista',    'color' => '#f43f5e', 'comision' => 12, 'telefono' => '612345003'],
            ['nombre' => 'Diego',   'apellidos' => 'Romero',   'cargo' => 'Estilista',    'color' => '#0ea5e9', 'comision' => 10, 'telefono' => '612345004'],
            ['nombre' => 'Elena',   'apellidos' => 'Castro',   'cargo' => 'Esteticista',  'color' => '#d946ef', 'comision' => 8,  'telefono' => '612345005'],
            ['nombre' => 'Javier',  'apellidos' => 'Vega',     'cargo' => 'Recepcionista','color' => '#64748b', 'comision' => 0,  'telefono' => '612345006'],
        ];
        foreach ($empleadosExtra as $e) {
            Empleado::firstOrCreate(
                ['empresa_id' => $empresaId, 'nombre' => $e['nombre'], 'apellidos' => $e['apellidos']],
                array_merge($e, ['empresa_id' => $empresaId, 'fecha_alta' => now()->subMonths(rand(2, 18)), 'activo' => true])
            );
        }

        $empleadoIds = Empleado::where('empresa_id', $empresaId)->pluck('id')->toArray();

        // ============== CLIENTES (10) ==============
        $clientesData = [
            ['nombre' => 'Carmen',   'apellidos' => 'Jiménez Ortiz',    'telefono' => '666010101', 'email' => 'carmen.jimenez@example.com', 'genero' => 'femenino',  'fecha_nacimiento' => '1985-03-12', 'ciudad' => 'Lima'],
            ['nombre' => 'Patricia', 'apellidos' => 'Moreno Díaz',      'telefono' => '666020202', 'email' => 'patricia.moreno@example.com','genero' => 'femenino',  'fecha_nacimiento' => '1992-07-25', 'ciudad' => 'Lima'],
            ['nombre' => 'Andrea',   'apellidos' => 'Soler Mas',        'telefono' => '666030303', 'email' => 'andrea.soler@example.com',   'genero' => 'femenino',  'fecha_nacimiento' => '1978-11-04', 'ciudad' => 'Arequipa'],
            ['nombre' => 'Miguel',   'apellidos' => 'Torres Navarro',   'telefono' => '666040404', 'email' => 'miguel.torres@example.com',  'genero' => 'masculino', 'fecha_nacimiento' => '1989-05-18', 'ciudad' => 'Lima'],
            ['nombre' => 'Beatriz',  'apellidos' => 'Reyes Santos',     'telefono' => '666050505', 'email' => 'bea.reyes@example.com',      'genero' => 'femenino',  'fecha_nacimiento' => '1995-01-30', 'ciudad' => 'Trujillo'],
            ['nombre' => 'Roberto',  'apellidos' => 'Iglesias Vidal',   'telefono' => '666060606', 'email' => 'roberto.iglesias@example.com','genero' => 'masculino','fecha_nacimiento' => '1980-09-09', 'ciudad' => 'Lima'],
            ['nombre' => 'Cristina', 'apellidos' => 'Marín Cabrera',    'telefono' => '666070707', 'email' => 'cristina.marin@example.com', 'genero' => 'femenino',  'fecha_nacimiento' => '1990-04-22', 'ciudad' => 'Cusco'],
            ['nombre' => 'Daniel',   'apellidos' => 'Herrera Bravo',    'telefono' => '666080808', 'email' => 'daniel.herrera@example.com', 'genero' => 'masculino', 'fecha_nacimiento' => '1987-12-15', 'ciudad' => 'Lima'],
            ['nombre' => 'Isabel',   'apellidos' => 'Núñez Ramos',      'telefono' => '666090909', 'email' => 'isabel.nunez@example.com',   'genero' => 'femenino',  'fecha_nacimiento' => '1975-06-08', 'ciudad' => 'Lima'],
            ['nombre' => 'Sergio',   'apellidos' => 'Blanco Pascual',   'telefono' => '666100100', 'email' => 'sergio.blanco@example.com',  'genero' => 'masculino', 'fecha_nacimiento' => '1993-08-19', 'ciudad' => 'Chiclayo'],
        ];

        $now = Carbon::now();
        foreach ($clientesData as $i => $c) {
            $fechaAlta = $now->copy()->subDays(rand(20, 180));
            Cliente::firstOrCreate(
                ['empresa_id' => $empresaId, 'telefono' => $c['telefono']],
                array_merge($c, [
                    'empresa_id' => $empresaId,
                    'fecha_alta' => $fechaAlta,
                    'ultima_visita' => $now->copy()->subDays(rand(0, 40)),
                    'puntos_fidelidad' => rand(0, 250),
                    'saldo' => rand(0, 1) ? rand(0, 50) : 0,
                    'acepta_marketing' => true,
                    'acepta_rgpd' => true,
                    'activo' => true,
                    'codigo' => 'C-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'created_at' => $fechaAlta,
                    'updated_at' => $fechaAlta,
                ])
            );
        }

        $clienteIds = Cliente::where('empresa_id', $empresaId)->pluck('id')->toArray();

        // ============== PRODUCTOS extra (hasta 10+) ==============
        $productosExtra = [
            ['nombre' => 'Sérum reparador 50ml', 'marca' => 'Kerastase', 'precio_compra' => 18.00, 'precio_venta' => 36.00, 'stock' => 10, 'stock_minimo' => 2],
            ['nombre' => 'Aceite de argán 100ml', 'marca' => 'Moroccanoil', 'precio_compra' => 15.00, 'precio_venta' => 30.00, 'stock' => 14, 'stock_minimo' => 3],
            ['nombre' => 'Champú anticaspa 400ml', 'marca' => 'Vichy', 'precio_compra' => 7.50, 'precio_venta' => 15.50, 'stock' => 18, 'stock_minimo' => 4],
            ['nombre' => 'Spray protector calor', 'marca' => 'Tresemmé', 'precio_compra' => 5.50, 'precio_venta' => 12.50, 'stock' => 22, 'stock_minimo' => 5],
            ['nombre' => 'Crema definidora rizos', 'marca' => 'Pantene', 'precio_compra' => 4.80, 'precio_venta' => 11.00, 'stock' => 16, 'stock_minimo' => 4],
        ];
        foreach ($productosExtra as $p) {
            Producto::firstOrCreate(
                ['empresa_id' => $empresaId, 'nombre' => $p['nombre']],
                array_merge($p, [
                    'empresa_id' => $empresaId,
                    'impuesto' => 18.00,
                    'controlar_stock' => true,
                    'vendible' => true,
                    'activo' => true,
                ])
            );
        }

        $productos = Producto::where('empresa_id', $empresaId)->get();
        $servicios = Servicio::where('empresa_id', $empresaId)->get();

        // ============== BONOS (10) ==============
        $tiposBonosIds = TipoBono::where('empresa_id', $empresaId)->pluck('id')->toArray();
        for ($i = 0; $i < 10; $i++) {
            $clienteId = $clienteIds[array_rand($clienteIds)];
            $servicio = $servicios->random();
            $sesiones = rand(3, 10);
            $precio = round($servicio->precio * $sesiones * 0.85, 2);
            $sesionesUsadas = rand(0, $sesiones);
            $fechaCompra = $now->copy()->subDays(rand(5, 90));

            $bono = Bono::create([
                'empresa_id' => $empresaId,
                'cliente_id' => $clienteId,
                'tipo_bono_id' => count($tiposBonosIds) ? $tiposBonosIds[array_rand($tiposBonosIds)] : null,
                'servicio_id' => $servicio->id,
                'codigo' => 'BONO-' . strtoupper(Str::random(8)),
                'sesiones_total' => $sesiones,
                'sesiones_usadas' => $sesionesUsadas,
                'precio' => $precio,
                'fecha_compra' => $fechaCompra,
                'fecha_caducidad' => $fechaCompra->copy()->addYear(),
                'estado' => $sesionesUsadas >= $sesiones ? 'agotado' : 'activo',
                'created_at' => $fechaCompra,
                'updated_at' => $fechaCompra,
            ]);

            for ($u = 0; $u < $sesionesUsadas; $u++) {
                UsoBono::create([
                    'bono_id' => $bono->id,
                    'empleado_id' => $empleadoIds[array_rand($empleadoIds)],
                    'fecha' => $fechaCompra->copy()->addDays(rand(1, 60)),
                    'notas' => null,
                ]);
            }
        }

        // ============== CITAS (15+ distribuidas) ==============
        $estadosCitas = ['finalizada', 'finalizada', 'finalizada', 'confirmada', 'pendiente', 'cancelada'];
        $citasGeneradas = [];

        // Citas pasadas (últimos 30 días)
        for ($i = 0; $i < 18; $i++) {
            $diasAtras = rand(1, 30);
            $fecha = $now->copy()->subDays($diasAtras);
            $hora = sprintf('%02d:%02d:00', rand(9, 18), [0, 15, 30, 45][array_rand([0, 1, 2, 3])]);
            $estado = ($diasAtras > 1) ? 'finalizada' : $estadosCitas[array_rand($estadosCitas)];
            $citasGeneradas[] = $this->crearCita($empresaId, $clienteIds, $empleadoIds, $servicios, $fecha, $hora, $estado);
        }

        // Citas hoy
        for ($i = 0; $i < 5; $i++) {
            $hora = sprintf('%02d:%02d:00', rand(10, 19), [0, 15, 30, 45][array_rand([0, 1, 2, 3])]);
            $estado = ['confirmada', 'confirmada', 'pendiente', 'finalizada', 'en_curso'][array_rand([0, 1, 2, 3, 4])];
            $citasGeneradas[] = $this->crearCita($empresaId, $clienteIds, $empleadoIds, $servicios, $now->copy(), $hora, $estado);
        }

        // Citas futuras (próximos 14 días)
        for ($i = 0; $i < 8; $i++) {
            $fecha = $now->copy()->addDays(rand(1, 14));
            $hora = sprintf('%02d:%02d:00', rand(9, 19), [0, 15, 30, 45][array_rand([0, 1, 2, 3])]);
            $estado = rand(0, 2) ? 'confirmada' : 'pendiente';
            $citasGeneradas[] = $this->crearCita($empresaId, $clienteIds, $empleadoIds, $servicios, $fecha, $hora, $estado);
        }

        // ============== CAJAS Y VENTAS (distribuidas en 30 días) ==============
        // Crear cajas cerradas de los últimos 30 días + caja abierta hoy
        $metodosPago = ['efectivo', 'tarjeta', 'efectivo', 'tarjeta', 'tarjeta', 'yapeplin', 'transferencia'];

        for ($d = 30; $d >= 1; $d--) {
            // Saltar algunos días aleatorios para variedad
            if (rand(1, 10) === 1) continue;

            $fecha = $now->copy()->subDays($d);
            // Saltar domingos
            if ($fecha->dayOfWeek === Carbon::SUNDAY) continue;

            $caja = Caja::create([
                'empresa_id' => $empresaId,
                'user_apertura_id' => $userId,
                'user_cierre_id' => $userId,
                'fecha_apertura' => $fecha->copy()->setTime(9, 0),
                'fecha_cierre' => $fecha->copy()->setTime(20, 30),
                'importe_inicial' => 100.00,
                'importe_final' => 0,
                'estado' => 'cerrada',
                'created_at' => $fecha->copy()->setTime(9, 0),
                'updated_at' => $fecha->copy()->setTime(20, 30),
            ]);

            // Generar 2-7 ventas para ese día
            $ventasDia = rand(2, 7);
            $totalEfectivo = $totalTarjeta = $totalYapePlin = $totalTransfer = 0;

            for ($v = 0; $v < $ventasDia; $v++) {
                $hora = sprintf('%02d:%02d:00', rand(9, 19), rand(0, 59));
                $fechaVenta = $fecha->copy()->setTimeFromTimeString($hora);
                $metodo = $metodosPago[array_rand($metodosPago)];
                $clienteId = rand(0, 3) ? $clienteIds[array_rand($clienteIds)] : null;
                $empleadoId = $empleadoIds[array_rand($empleadoIds)];

                $items = [];
                $numItems = rand(1, 3);
                for ($n = 0; $n < $numItems; $n++) {
                    if (rand(0, 2) > 0) { // 2/3 servicios
                        $servicio = $servicios->random();
                        $items[] = ['tipo' => 'servicio', 'ref' => $servicio->id, 'concepto' => $servicio->nombre, 'precio' => (float) $servicio->precio, 'cantidad' => 1, 'imp' => (float) $servicio->impuesto];
                    } else {
                        $producto = $productos->random();
                        $items[] = ['tipo' => 'producto', 'ref' => $producto->id, 'concepto' => $producto->nombre, 'precio' => (float) $producto->precio_venta, 'cantidad' => 1, 'imp' => (float) $producto->impuesto];
                    }
                }

                $subtotal = 0; $impuesto = 0;
                foreach ($items as $it) {
                    $linea = $it['precio'] * $it['cantidad'];
                    $imp = $linea - ($linea / (1 + $it['imp'] / 100));
                    $subtotal += $linea - $imp;
                    $impuesto += $imp;
                }
                $total = round($subtotal + $impuesto, 2);

                $venta = Venta::create([
                    'empresa_id' => $empresaId,
                    'cliente_id' => $clienteId,
                    'empleado_id' => $empleadoId,
                    'user_id' => $userId,
                    'caja_id' => $caja->id,
                    'numero' => 'V-' . $fechaVenta->format('Ymd') . '-' . str_pad((Venta::count() + 1), 5, '0', STR_PAD_LEFT),
                    'fecha' => $fechaVenta,
                    'subtotal' => round($subtotal, 2),
                    'descuento' => 0,
                    'impuesto' => round($impuesto, 2),
                    'total' => $total,
                    'metodo_pago' => $metodo,
                    'importe_pagado' => $total,
                    'cambio' => 0,
                    'estado' => 'pagada',
                    'created_at' => $fechaVenta,
                    'updated_at' => $fechaVenta,
                ]);

                foreach ($items as $it) {
                    DetalleVenta::create([
                        'venta_id' => $venta->id,
                        'tipo' => $it['tipo'],
                        'referencia_id' => $it['ref'],
                        'empleado_id' => $empleadoId,
                        'concepto' => $it['concepto'],
                        'cantidad' => $it['cantidad'],
                        'precio_unitario' => $it['precio'],
                        'descuento' => 0,
                        'impuesto_porcentaje' => $it['imp'],
                        'subtotal' => round($it['precio'] * $it['cantidad'] / (1 + $it['imp'] / 100), 2),
                        'total' => round($it['precio'] * $it['cantidad'], 2),
                        'created_at' => $fechaVenta,
                        'updated_at' => $fechaVenta,
                    ]);

                    if ($it['tipo'] === 'producto') {
                        $producto = Producto::find($it['ref']);
                        if ($producto && $producto->controlar_stock) {
                            MovimientoStock::create([
                                'empresa_id' => $empresaId,
                                'producto_id' => $producto->id,
                                'user_id' => $userId,
                                'tipo' => 'venta',
                                'cantidad' => $it['cantidad'],
                                'stock_anterior' => $producto->stock + $it['cantidad'],
                                'stock_nuevo' => $producto->stock,
                                'precio_unitario' => $it['precio'],
                                'referencia' => $venta->numero,
                                'created_at' => $fechaVenta,
                                'updated_at' => $fechaVenta,
                            ]);
                        }
                    }
                }

                PagoVenta::create([
                    'venta_id' => $venta->id,
                    'metodo' => $metodo,
                    'importe' => $total,
                    'fecha' => $fechaVenta,
                    'created_at' => $fechaVenta,
                    'updated_at' => $fechaVenta,
                ]);

                MovimientoCaja::create([
                    'caja_id' => $caja->id,
                    'user_id' => $userId,
                    'tipo' => 'venta',
                    'importe' => $total,
                    'metodo_pago' => $metodo,
                    'concepto' => 'Venta ' . $venta->numero,
                    'referencia' => $venta->numero,
                    'fecha' => $fechaVenta,
                    'created_at' => $fechaVenta,
                    'updated_at' => $fechaVenta,
                ]);

                if ($metodo === 'efectivo') $totalEfectivo += $total;
                elseif ($metodo === 'tarjeta') $totalTarjeta += $total;
                elseif ($metodo === 'yapeplin') $totalYapePlin += $total;
                elseif ($metodo === 'transferencia') $totalTransfer += $total;
            }

            // Cerrar caja con totales
            $totalDia = $totalEfectivo + $totalTarjeta + $totalYapePlin + $totalTransfer;
            $caja->update([
                'importe_efectivo' => round(100 + $totalEfectivo, 2),
                'importe_tarjeta' => round($totalTarjeta, 2),
                'importe_transferencia' => round($totalTransfer, 2),
                'importe_otros' => round($totalYapePlin, 2),
                'total_ventas' => round($totalDia, 2),
                'total_ingresos' => 0,
                'total_gastos' => 0,
                'importe_final' => round(100 + $totalEfectivo, 2),
                'descuadre' => 0,
            ]);
        }

        // Caja abierta para HOY
        $cajaHoy = Caja::create([
            'empresa_id' => $empresaId,
            'user_apertura_id' => $userId,
            'fecha_apertura' => $now->copy()->setTime(9, 0),
            'importe_inicial' => 100.00,
            'estado' => 'abierta',
            'notas_apertura' => 'Apertura de caja del día',
            'created_at' => $now->copy()->setTime(9, 0),
            'updated_at' => $now->copy()->setTime(9, 0),
        ]);

        MovimientoCaja::create([
            'caja_id' => $cajaHoy->id,
            'user_id' => $userId,
            'tipo' => 'apertura',
            'importe' => 100,
            'metodo_pago' => 'efectivo',
            'concepto' => 'Apertura de caja',
            'fecha' => $now->copy()->setTime(9, 0),
        ]);

        // Ventas de hoy (3-5)
        for ($v = 0; $v < rand(3, 5); $v++) {
            $hora = sprintf('%02d:%02d:00', rand(9, min(19, $now->hour)), rand(0, 59));
            $fechaVenta = $now->copy()->setTimeFromTimeString($hora);
            if ($fechaVenta->gt($now)) continue;
            $metodo = $metodosPago[array_rand($metodosPago)];
            $clienteId = rand(0, 3) ? $clienteIds[array_rand($clienteIds)] : null;
            $empleadoId = $empleadoIds[array_rand($empleadoIds)];

            $servicio = $servicios->random();
            $precio = (float) $servicio->precio;
                $imp = $precio - ($precio / 1.18);
            $subtotal = $precio - $imp;

            $venta = Venta::create([
                'empresa_id' => $empresaId,
                'cliente_id' => $clienteId,
                'empleado_id' => $empleadoId,
                'user_id' => $userId,
                'caja_id' => $cajaHoy->id,
                'numero' => 'V-' . $fechaVenta->format('Ymd') . '-' . str_pad((Venta::count() + 1), 5, '0', STR_PAD_LEFT),
                'fecha' => $fechaVenta,
                'subtotal' => round($subtotal, 2),
                'impuesto' => round($imp, 2),
                'total' => round($precio, 2),
                'metodo_pago' => $metodo,
                'importe_pagado' => round($precio, 2),
                'estado' => 'pagada',
                'created_at' => $fechaVenta,
                'updated_at' => $fechaVenta,
            ]);

            DetalleVenta::create([
                'venta_id' => $venta->id,
                'tipo' => 'servicio',
                'referencia_id' => $servicio->id,
                'empleado_id' => $empleadoId,
                'concepto' => $servicio->nombre,
                'cantidad' => 1,
                'precio_unitario' => $precio,
                'impuesto_porcentaje' => 21,
                'subtotal' => round($subtotal, 2),
                'total' => round($precio, 2),
                'created_at' => $fechaVenta,
                'updated_at' => $fechaVenta,
            ]);

            MovimientoCaja::create([
                'caja_id' => $cajaHoy->id,
                'user_id' => $userId,
                'tipo' => 'venta',
                'importe' => round($precio, 2),
                'metodo_pago' => $metodo,
                'concepto' => 'Venta ' . $venta->numero,
                'referencia' => $venta->numero,
                'fecha' => $fechaVenta,
            ]);
        }

        $this->command->info("✓ Datos demo generados:");
        $this->command->info("  - Clientes: " . Cliente::count());
        $this->command->info("  - Empleados: " . Empleado::count());
        $this->command->info("  - Servicios: " . Servicio::count());
        $this->command->info("  - Productos: " . Producto::count());
        $this->command->info("  - Citas: " . Cita::count());
        $this->command->info("  - Bonos: " . Bono::count());
        $this->command->info("  - Ventas: " . Venta::count());
        $this->command->info("  - Cajas: " . Caja::count());
    }

    private function crearCita(int $empresaId, array $clienteIds, array $empleadoIds, $servicios, Carbon $fecha, string $hora, string $estado): Cita
    {
        $servicio = $servicios->random();
        $duracion = (int) $servicio->duracion;
        $horaFin = Carbon::parse($hora)->addMinutes($duracion)->format('H:i:s');
        $clienteId = $clienteIds[array_rand($clienteIds)];
        $empleadoId = $empleadoIds[array_rand($empleadoIds)];

        $cita = Cita::create([
            'empresa_id' => $empresaId,
            'cliente_id' => $clienteId,
            'empleado_id' => $empleadoId,
            'fecha' => $fecha->format('Y-m-d'),
            'hora_inicio' => $hora,
            'hora_fin' => $horaFin,
            'duracion_total' => $duracion,
            'precio_total' => $servicio->precio,
            'estado' => $estado,
            'origen' => 'manual',
            'created_at' => $fecha,
            'updated_at' => $fecha,
        ]);

        CitaServicio::create([
            'cita_id' => $cita->id,
            'servicio_id' => $servicio->id,
            'empleado_id' => $empleadoId,
            'duracion' => $duracion,
            'precio' => $servicio->precio,
            'orden' => 0,
        ]);

        return $cita;
    }
}
