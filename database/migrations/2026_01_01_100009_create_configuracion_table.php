<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('clave', 100);
            $table->text('valor')->nullable();
            $table->string('grupo', 50)->default('general');
            $table->string('tipo', 30)->default('string'); // string, integer, boolean, json
            $table->text('descripcion')->nullable();
            $table->timestamps();

            $table->unique(['empresa_id', 'clave']);
        });

        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nombre');
            $table->string('archivo');
            $table->bigInteger('tamano')->default(0);
            $table->enum('tipo', ['manual', 'automatico'])->default('manual');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('logs_actividad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('accion', 100);
            $table->string('modulo', 50);
            $table->text('descripcion')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs_actividad');
        Schema::dropIfExists('backups');
        Schema::dropIfExists('configuraciones');
    }
};
