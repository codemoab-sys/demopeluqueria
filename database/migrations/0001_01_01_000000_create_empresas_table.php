<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cif', 30)->nullable();
            $table->string('direccion')->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('codigo_postal', 15)->nullable();
            $table->string('provincia', 100)->nullable();
            $table->string('pais', 100)->default('Perú');
            $table->string('telefono', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('web')->nullable();
            $table->string('logo')->nullable();
            $table->string('simbolo_moneda', 5)->default('S/.');
            $table->string('codigo_moneda', 5)->default('PEN');
            $table->decimal('impuesto_default', 5, 2)->default(18.00);
            $table->string('zona_horaria', 50)->default('America/Lima');
            $table->string('idioma', 5)->default('es');
            $table->string('formato_fecha', 20)->default('d/m/Y');
            $table->time('hora_apertura')->default('09:00');
            $table->time('hora_cierre')->default('20:00');
            $table->json('dias_laborables')->nullable();
            $table->integer('intervalo_citas')->default(15);
            $table->text('mensaje_ticket')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
