<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('user_apertura_id')->constrained('users');
            $table->foreignId('user_cierre_id')->nullable()->constrained('users');
            $table->dateTime('fecha_apertura');
            $table->dateTime('fecha_cierre')->nullable();
            $table->decimal('importe_inicial', 10, 2)->default(0);
            $table->decimal('importe_final', 10, 2)->nullable();
            $table->decimal('importe_efectivo', 10, 2)->default(0);
            $table->decimal('importe_tarjeta', 10, 2)->default(0);
            $table->decimal('importe_transferencia', 10, 2)->default(0);
            $table->decimal('importe_otros', 10, 2)->default(0);
            $table->decimal('total_ventas', 10, 2)->default(0);
            $table->decimal('total_ingresos', 10, 2)->default(0);
            $table->decimal('total_gastos', 10, 2)->default(0);
            $table->decimal('descuadre', 10, 2)->default(0);
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');
            $table->text('notas_apertura')->nullable();
            $table->text('notas_cierre')->nullable();
            $table->timestamps();
        });

        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained('cajas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('tipo', ['ingreso', 'gasto', 'venta', 'devolucion', 'apertura', 'cierre']);
            $table->decimal('importe', 10, 2);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'yapeplin', 'otro'])->default('efectivo');
            $table->string('concepto');
            $table->text('descripcion')->nullable();
            $table->string('referencia')->nullable();
            $table->dateTime('fecha');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_caja');
        Schema::dropIfExists('cajas');
    }
};
