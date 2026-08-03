<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_bonos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->foreignId('servicio_id')->nullable()->constrained('servicios')->nullOnDelete();
            $table->integer('sesiones')->default(1);
            $table->decimal('precio', 10, 2)->default(0);
            $table->decimal('precio_sesion', 10, 2)->default(0);
            $table->integer('validez_dias')->default(365);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('bonos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('tipo_bono_id')->nullable()->constrained('tipos_bonos')->nullOnDelete();
            $table->foreignId('servicio_id')->nullable()->constrained('servicios')->nullOnDelete();
            $table->string('codigo', 50)->unique();
            $table->integer('sesiones_total');
            $table->integer('sesiones_usadas')->default(0);
            $table->decimal('precio', 10, 2);
            $table->date('fecha_compra');
            $table->date('fecha_caducidad')->nullable();
            $table->enum('estado', ['activo', 'agotado', 'caducado', 'cancelado'])->default('activo');
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('uso_bonos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bono_id')->constrained('bonos')->cascadeOnDelete();
            $table->foreignId('cita_id')->nullable()->constrained('citas')->nullOnDelete();
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->nullOnDelete();
            $table->date('fecha');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uso_bonos');
        Schema::dropIfExists('bonos');
        Schema::dropIfExists('tipos_bonos');
    }
};
