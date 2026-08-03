<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('codigo', 30)->nullable();
            $table->string('nombre');
            $table->string('apellidos')->nullable();
            $table->string('dni', 30)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('email')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ['masculino', 'femenino', 'otro'])->nullable();
            $table->string('direccion')->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('codigo_postal', 15)->nullable();
            $table->string('foto')->nullable();
            $table->text('notas')->nullable();
            $table->text('alergias')->nullable();
            $table->text('preferencias')->nullable();
            $table->integer('puntos_fidelidad')->default(0);
            $table->decimal('saldo', 10, 2)->default(0);
            $table->boolean('acepta_marketing')->default(false);
            $table->boolean('acepta_rgpd')->default(false);
            $table->date('fecha_alta')->nullable();
            $table->date('ultima_visita')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['empresa_id', 'nombre']);
            $table->index(['empresa_id', 'telefono']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
