<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ENT 2.3 — Tarea 1: estructura de responsable de transporte (persona y licencia).
     * Carnet de identidad (no DNI); teléfono pensado en formato Bolivia +591XXXXXXXX.
     */
    public function up(): void
    {
        Schema::table('transportistas', function (Blueprint $table) {
            $table->string('apellido', 160)->nullable()->after('nombre');
            $table->string('licencia', 64)->nullable()->after('email');
            $table->string('tipo_licencia', 32)->nullable()->after('licencia');
            $table->date('fecha_vencimiento_licencia')->nullable()->after('tipo_licencia');
            $table->string('estado', 30)->default('activo')->after('fecha_vencimiento_licencia');
        });

        DB::table('transportistas')->where('activo', false)->update(['estado' => 'inactivo']);

        Schema::table('transportistas', function (Blueprint $table) {
            $table->dropColumn('activo');
        });

        Schema::table('transportistas', function (Blueprint $table) {
            $table->renameColumn('documento_identidad', 'carnet_identidad');
        });
    }

    public function down(): void
    {
        Schema::table('transportistas', function (Blueprint $table) {
            $table->renameColumn('carnet_identidad', 'documento_identidad');
        });

        Schema::table('transportistas', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('email');
        });

        DB::table('transportistas')->where('estado', 'inactivo')->update(['activo' => false]);

        Schema::table('transportistas', function (Blueprint $table) {
            $table->dropColumn([
                'estado',
                'fecha_vencimiento_licencia',
                'tipo_licencia',
                'licencia',
                'apellido',
            ]);
        });
    }
};
