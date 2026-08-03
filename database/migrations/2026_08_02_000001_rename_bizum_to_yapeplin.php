<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `ventas` MODIFY `metodo_pago` ENUM('efectivo','tarjeta','transferencia','bizum','yapeplin','mixto','otro') NOT NULL DEFAULT 'efectivo'");
        DB::statement("ALTER TABLE `pagos_ventas` MODIFY `metodo` ENUM('efectivo','tarjeta','transferencia','bizum','yapeplin','bono','saldo','otro') NOT NULL");
        DB::statement("ALTER TABLE `movimientos_caja` MODIFY `metodo_pago` ENUM('efectivo','tarjeta','transferencia','bizum','yapeplin','otro') NOT NULL DEFAULT 'efectivo'");

        DB::table('ventas')->where('metodo_pago', 'bizum')->update(['metodo_pago' => 'yapeplin']);
        DB::table('pagos_ventas')->where('metodo', 'bizum')->update(['metodo' => 'yapeplin']);
        DB::table('movimientos_caja')->where('metodo_pago', 'bizum')->update(['metodo_pago' => 'yapeplin']);

        DB::statement("ALTER TABLE `ventas` MODIFY `metodo_pago` ENUM('efectivo','tarjeta','transferencia','yapeplin','mixto','otro') NOT NULL DEFAULT 'efectivo'");
        DB::statement("ALTER TABLE `pagos_ventas` MODIFY `metodo` ENUM('efectivo','tarjeta','transferencia','yapeplin','bono','saldo','otro') NOT NULL");
        DB::statement("ALTER TABLE `movimientos_caja` MODIFY `metodo_pago` ENUM('efectivo','tarjeta','transferencia','yapeplin','otro') NOT NULL DEFAULT 'efectivo'");
    }

    public function down(): void
    {
        DB::table('ventas')->where('metodo_pago', 'yapeplin')->update(['metodo_pago' => 'bizum']);
        DB::table('pagos_ventas')->where('metodo', 'yapeplin')->update(['metodo' => 'bizum']);
        DB::table('movimientos_caja')->where('metodo_pago', 'yapeplin')->update(['metodo_pago' => 'bizum']);

        DB::statement("ALTER TABLE `ventas` MODIFY `metodo_pago` ENUM('efectivo','tarjeta','transferencia','bizum','mixto','otro') NOT NULL DEFAULT 'efectivo'");
        DB::statement("ALTER TABLE `pagos_ventas` MODIFY `metodo` ENUM('efectivo','tarjeta','transferencia','bizum','bono','saldo','otro') NOT NULL");
        DB::statement("ALTER TABLE `movimientos_caja` MODIFY `metodo_pago` ENUM('efectivo','tarjeta','transferencia','bizum','otro') NOT NULL DEFAULT 'efectivo'");
    }
};
