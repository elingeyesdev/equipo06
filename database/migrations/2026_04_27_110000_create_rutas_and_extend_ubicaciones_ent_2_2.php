<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ENT 2.2 - Tarea 2: rutas de transporte y orden de puntos (ubicaciones) por ruta.
     */
    public function up(): void
    {
        Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('envio_id')->nullable()->constrained('envios')->nullOnDelete();
            $table->string('nombre', 160)->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();

            $table->index('envio_id');
        });

        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->foreignId('ruta_id')->nullable()->after('id')->constrained('rutas')->nullOnDelete();
            $table->unsignedSmallInteger('orden')->nullable()->after('ruta_id');
            $table->index(['ruta_id', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropForeign(['ruta_id']);
            $table->dropColumn(['ruta_id', 'orden']);
        });

        Schema::dropIfExists('rutas');
    }
};
