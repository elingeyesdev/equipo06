<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ENT 2.2 - Tarea 1: ubicaciones geográficas vinculables a envíos (sin CRUD aún).
     */
    public function up(): void
    {
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_ubicacion', 160);
            $table->string('tipo', 30);
            $table->string('direccion', 255)->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->text('descripcion')->nullable();
            $table->foreignId('envio_id')->nullable()->constrained('envios')->nullOnDelete();
            $table->timestamps();

            $table->index(['envio_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubicaciones');
    }
};
