<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $validos = ['activo', 'cerrado', 'anulado'];

        DB::table('lotes')
            ->whereNotIn('estado', $validos)
            ->update(['estado' => 'activo']);
    }

    public function down(): void
    {
        //
    }
};
