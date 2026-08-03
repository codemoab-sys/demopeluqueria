<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias_servicios')->nullOnDelete();
            $table->string('codigo', 30)->nullable();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->integer('duracion')->default(30); // minutos
            $table->decimal('precio', 10, 2)->default(0);
            $table->decimal('impuesto', 5, 2)->default(18.00);
            $table->decimal('comision', 5, 2)->default(0);
            $table->string('color', 10)->default('#a855f7');
            $table->string('imagen')->nullable();
            $table->boolean('reservable_online')->default(true);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
