<?php

namespace Database\Seeders;

use App\Models\CategoriaProducto;
use App\Models\CategoriaServicio;
use App\Models\Empleado;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\TipoBono;
use Illuminate\Database\Seeder;

class CatalogoSeeder extends Seeder
{
    public function run(): void
    {
        $empresaId = 1;

        // Categorías de servicios
        $catCorte = CategoriaServicio::firstOrCreate(['empresa_id' => $empresaId, 'nombre' => 'Corte y peinado'], ['color' => '#a855f7', 'icono' => 'bi-scissors']);
        $catColor = CategoriaServicio::firstOrCreate(['empresa_id' => $empresaId, 'nombre' => 'Color y mechas'], ['color' => '#ec4899', 'icono' => 'bi-palette']);
        $catTrat = CategoriaServicio::firstOrCreate(['empresa_id' => $empresaId, 'nombre' => 'Tratamientos'], ['color' => '#06b6d4', 'icono' => 'bi-droplet']);
        $catEst = CategoriaServicio::firstOrCreate(['empresa_id' => $empresaId, 'nombre' => 'Estética'], ['color' => '#f59e0b', 'icono' => 'bi-stars']);

        // Servicios
        $servicios = [
            ['categoria_id' => $catCorte->id, 'nombre' => 'Corte mujer', 'duracion' => 45, 'precio' => 25.00],
            ['categoria_id' => $catCorte->id, 'nombre' => 'Corte hombre', 'duracion' => 30, 'precio' => 18.00],
            ['categoria_id' => $catCorte->id, 'nombre' => 'Corte niño/a', 'duracion' => 30, 'precio' => 14.00],
            ['categoria_id' => $catCorte->id, 'nombre' => 'Peinado / recogido', 'duracion' => 45, 'precio' => 30.00],
            ['categoria_id' => $catCorte->id, 'nombre' => 'Brushing', 'duracion' => 30, 'precio' => 20.00],
            ['categoria_id' => $catColor->id, 'nombre' => 'Tinte completo', 'duracion' => 90, 'precio' => 55.00],
            ['categoria_id' => $catColor->id, 'nombre' => 'Mechas', 'duracion' => 120, 'precio' => 75.00],
            ['categoria_id' => $catColor->id, 'nombre' => 'Balayage', 'duracion' => 150, 'precio' => 95.00],
            ['categoria_id' => $catColor->id, 'nombre' => 'Decoloración', 'duracion' => 120, 'precio' => 80.00],
            ['categoria_id' => $catTrat->id, 'nombre' => 'Hidratación profunda', 'duracion' => 45, 'precio' => 35.00],
            ['categoria_id' => $catTrat->id, 'nombre' => 'Keratina', 'duracion' => 120, 'precio' => 120.00],
            ['categoria_id' => $catTrat->id, 'nombre' => 'Tratamiento anticaída', 'duracion' => 30, 'precio' => 28.00],
            ['categoria_id' => $catEst->id, 'nombre' => 'Manicura', 'duracion' => 45, 'precio' => 22.00],
            ['categoria_id' => $catEst->id, 'nombre' => 'Pedicura', 'duracion' => 60, 'precio' => 28.00],
            ['categoria_id' => $catEst->id, 'nombre' => 'Diseño de cejas', 'duracion' => 30, 'precio' => 12.00],
        ];

        foreach ($servicios as $s) {
            Servicio::firstOrCreate(
                ['empresa_id' => $empresaId, 'nombre' => $s['nombre']],
                array_merge($s, [
                    'empresa_id' => $empresaId,
                    'impuesto' => 18.00,
                    'comision' => 10.00,
                    'color' => '#a855f7',
                    'activo' => true,
                ])
            );
        }

        // Empleados de ejemplo
        $empleados = [
            ['nombre' => 'María', 'apellidos' => 'García', 'cargo' => 'Estilista senior', 'color' => '#ec4899', 'comision' => 12],
            ['nombre' => 'Laura', 'apellidos' => 'Martínez', 'cargo' => 'Colorista', 'color' => '#a855f7', 'comision' => 10],
            ['nombre' => 'Carlos', 'apellidos' => 'Pérez', 'cargo' => 'Barbero', 'color' => '#06b6d4', 'comision' => 10],
            ['nombre' => 'Ana', 'apellidos' => 'López', 'cargo' => 'Esteticista', 'color' => '#f59e0b', 'comision' => 8],
        ];
        foreach ($empleados as $e) {
            Empleado::firstOrCreate(
                ['empresa_id' => $empresaId, 'nombre' => $e['nombre'], 'apellidos' => $e['apellidos']],
                array_merge($e, [
                    'empresa_id' => $empresaId,
                    'fecha_alta' => now(),
                    'activo' => true,
                ])
            );
        }

        // Categorías productos
        $catProd1 = CategoriaProducto::firstOrCreate(['empresa_id' => $empresaId, 'nombre' => 'Cuidado capilar'], ['color' => '#a855f7']);
        $catProd2 = CategoriaProducto::firstOrCreate(['empresa_id' => $empresaId, 'nombre' => 'Coloración'], ['color' => '#ec4899']);
        $catProd3 = CategoriaProducto::firstOrCreate(['empresa_id' => $empresaId, 'nombre' => 'Estilismo'], ['color' => '#f59e0b']);

        // Productos
        $productos = [
            ['categoria_id' => $catProd1->id, 'nombre' => 'Champú hidratante 500ml', 'marca' => 'Loreal', 'precio_compra' => 8.50, 'precio_venta' => 18.50, 'stock' => 15, 'stock_minimo' => 3],
            ['categoria_id' => $catProd1->id, 'nombre' => 'Acondicionador 500ml', 'marca' => 'Loreal', 'precio_compra' => 9.00, 'precio_venta' => 19.50, 'stock' => 12, 'stock_minimo' => 3],
            ['categoria_id' => $catProd1->id, 'nombre' => 'Mascarilla nutritiva 250ml', 'marca' => 'Schwarzkopf', 'precio_compra' => 12.00, 'precio_venta' => 26.00, 'stock' => 8, 'stock_minimo' => 2],
            ['categoria_id' => $catProd3->id, 'nombre' => 'Spray fijador fuerte', 'marca' => 'Wella', 'precio_compra' => 6.50, 'precio_venta' => 14.00, 'stock' => 20, 'stock_minimo' => 5],
            ['categoria_id' => $catProd3->id, 'nombre' => 'Cera moldeadora 100ml', 'marca' => 'Redken', 'precio_compra' => 8.00, 'precio_venta' => 16.50, 'stock' => 10, 'stock_minimo' => 2],
            ['categoria_id' => $catProd2->id, 'nombre' => 'Tinte rubio platino 60ml', 'marca' => 'Loreal', 'precio_compra' => 4.50, 'precio_venta' => 12.00, 'stock' => 25, 'stock_minimo' => 5],
        ];
        foreach ($productos as $p) {
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

        // Tipos de bonos
        $bonos = [
            ['nombre' => 'Bono 5 cortes mujer', 'sesiones' => 5, 'precio' => 100.00],
            ['nombre' => 'Bono 10 brushing', 'sesiones' => 10, 'precio' => 170.00],
            ['nombre' => 'Bono 5 manicuras', 'sesiones' => 5, 'precio' => 95.00],
        ];
        foreach ($bonos as $b) {
            TipoBono::firstOrCreate(
                ['empresa_id' => $empresaId, 'nombre' => $b['nombre']],
                array_merge($b, [
                    'empresa_id' => $empresaId,
                    'precio_sesion' => $b['precio'] / $b['sesiones'],
                    'validez_dias' => 365,
                    'activo' => true,
                ])
            );
        }
    }
}
