<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('caja_id')->nullable()->constrained('cajas')->nullOnDelete();
            $table->foreignId('cita_id')->nullable()->constrained('citas')->nullOnDelete();
            $table->string('numero', 50)->unique();
            $table->dateTime('fecha');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('impuesto', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'yapeplin', 'mixto', 'otro'])->default('efectivo');
            $table->decimal('importe_pagado', 10, 2)->default(0);
            $table->decimal('cambio', 10, 2)->default(0);
            $table->enum('estado', ['pendiente', 'pagada', 'parcial', 'anulada', 'devuelta'])->default('pagada');
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index(['empresa_id', 'fecha']);
        });

        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->cascadeOnDelete();
            $table->enum('tipo', ['servicio', 'producto', 'bono', 'otro']);
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->nullOnDelete();
            $table->string('concepto');
            $table->decimal('cantidad', 10, 2)->default(1);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('impuesto_porcentaje', 5, 2)->default(18.00);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });

        Schema::create('pagos_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->cascadeOnDelete();
            $table->enum('metodo', ['efectivo', 'tarjeta', 'transferencia', 'yapeplin', 'bono', 'saldo', 'otro']);
            $table->decimal('importe', 10, 2);
            $table->string('referencia')->nullable();
            $table->dateTime('fecha');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_ventas');
        Schema::dropIfExists('detalle_ventas');
        Schema::dropIfExists('ventas');
    }
};
