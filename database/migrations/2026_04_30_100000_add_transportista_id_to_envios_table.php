<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ENT 2.3 - Tarea 4:
     * Asociación simple de responsable de transporte directamente al envío (MVP).
     */
    public function up(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->foreignId('transportista_id')
                ->nullable()
                ->after('ubicacion_actual_id')
                ->constrained('transportistas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->dropConstrainedForeignId('transportista_id');
        });
    }
};
