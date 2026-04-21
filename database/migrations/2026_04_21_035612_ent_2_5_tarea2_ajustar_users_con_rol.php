<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nombre', 120)->nullable()->after('name');
            $table->string('apellido', 120)->nullable()->after('nombre');
            $table->string('telefono', 20)->nullable()->after('email');
            $table->enum('rol', ['admin', 'productor'])->default('productor')->after('password');
            $table->index('rol');
        });

        DB::table('users')
            ->whereNull('nombre')
            ->update(['nombre' => DB::raw('name')]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['rol']);
            $table->dropColumn(['rol', 'telefono', 'apellido', 'nombre']);
        });
    }
};
