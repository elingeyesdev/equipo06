<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ENT 2.2 - Tarea 4: ubicación actual del envío (MVP, sin tracking en vivo).
     */
    public function up(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->foreignId('ubicacion_actual_id')
                ->nullable()
                ->after('observaciones')
                ->constrained('ubicaciones')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->dropForeign(['ubicacion_actual_id']);
            $table->dropColumn('ubicacion_actual_id');
        });
    }
};
