<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dominio compacto de envíos: transportistas, vehículos, envíos, detalle y asignaciones.
     */
    public function up(): void
    {
        Schema::create('transportistas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 160);
            $table->string('documento_identidad', 32)->nullable();
            $table->string('telefono', 32);
            $table->string('email', 160)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transportista_id')->nullable()->constrained('transportistas')->nullOnDelete();
            $table->string('placa', 20)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('envios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 40)->unique();
            $table->string('origen', 255);
            $table->string('destino', 255);
            $table->string('estado', 30)->default('pendiente');
            $table->date('fecha_creacion');
            $table->date('fecha_programada')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('detalle_envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('envio_id')->constrained('envios')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
            $table->decimal('cantidad', 12, 3);
            $table->timestamps();

            $table->unique(['envio_id', 'producto_id']);
        });

        Schema::create('asignaciones_envio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('envio_id')->constrained('envios')->cascadeOnDelete();
            $table->foreignId('transportista_id')->constrained('transportistas')->restrictOnDelete();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->restrictOnDelete();
            $table->dateTime('fecha_asignacion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones_envio');
        Schema::dropIfExists('detalle_envios');
        Schema::dropIfExists('envios');
        Schema::dropIfExists('vehiculos');
        Schema::dropIfExists('transportistas');
    }
};
