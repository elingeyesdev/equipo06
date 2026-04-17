<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ENT 2.3 — Tarea 2: estructura de base de datos para responsables de transporte.
     *
     * La tabla física es `transportistas` (definición de columnas en migraciones anteriores
     * del dominio envíos y ENT 2.3 Tarea 1). Aquí se completan índices para filtros y
     * futuras relaciones con vehículos y envíos, sin añadir FKs en esta tarea.
     *
     * Campos del dominio: id, nombre, apellido, carnet_identidad, telefono (+591XXXXXXXX),
     * email (opcional), licencia, tipo_licencia, fecha_vencimiento_licencia, estado, timestamps.
     */
    public function up(): void
    {
        if (! Schema::hasTable('transportistas')) {
            return;
        }

        Schema::table('transportistas', function (Blueprint $table) {
            $table->index('estado');
            $table->index('carnet_identidad');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('transportistas')) {
            return;
        }

        Schema::table('transportistas', function (Blueprint $table) {
            $table->dropIndex(['estado']);
            $table->dropIndex(['carnet_identidad']);
        });
    }
};
