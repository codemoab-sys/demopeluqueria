<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('color', 10)->default('#8b5cf6');
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias_productos')->nullOnDelete();
            $table->string('codigo', 50)->nullable();
            $table->string('codigo_barras', 50)->nullable();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('marca')->nullable();
            $table->string('imagen')->nullable();
            $table->decimal('precio_compra', 10, 2)->default(0);
            $table->decimal('precio_venta', 10, 2)->default(0);
            $table->decimal('impuesto', 5, 2)->default(18.00);
            $table->decimal('stock', 10, 2)->default(0);
            $table->decimal('stock_minimo', 10, 2)->default(0);
            $table->string('unidad', 20)->default('uds');
            $table->boolean('controlar_stock')->default(true);
            $table->boolean('vendible')->default(true);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['empresa_id', 'codigo_barras']);
            $table->index(['empresa_id', 'nombre']);
        });

        Schema::create('movimientos_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('tipo', ['entrada', 'salida', 'venta', 'ajuste', 'devolucion']);
            $table->decimal('cantidad', 10, 2);
            $table->decimal('stock_anterior', 10, 2);
            $table->decimal('stock_nuevo', 10, 2);
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->string('motivo')->nullable();
            $table->string('referencia')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_stock');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('categorias_productos');
    }
};
