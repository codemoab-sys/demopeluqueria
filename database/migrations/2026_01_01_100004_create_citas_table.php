<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->nullOnDelete();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('duracion_total')->default(0);
            $table->decimal('precio_total', 10, 2)->default(0);
            $table->enum('estado', ['pendiente', 'confirmada', 'en_curso', 'finalizada', 'cancelada', 'no_asistio'])->default('pendiente');
            $table->string('color', 10)->nullable();
            $table->text('notas')->nullable();
            $table->boolean('recordatorio_enviado')->default(false);
            $table->string('origen', 30)->default('manual'); // manual, online, telefono
            $table->timestamps();

            $table->index(['empresa_id', 'fecha']);
            $table->index(['empleado_id', 'fecha']);
        });

        Schema::create('cita_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->cascadeOnDelete();
            $table->foreignId('servicio_id')->constrained('servicios')->cascadeOnDelete();
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->nullOnDelete();
            $table->integer('duracion');
            $table->decimal('precio', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cita_servicios');
        Schema::dropIfExists('citas');
    }
};
