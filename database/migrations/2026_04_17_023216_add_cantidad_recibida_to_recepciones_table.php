<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recepciones', function (Blueprint $table) {
            $table->decimal('cantidad_recibida', 12, 3)->nullable()->after('conforme');
            $table->string('resultado_validacion', 30)->nullable()->after('cantidad_recibida');
            $table->index('resultado_validacion');
        });
    }

    public function down(): void
    {
        Schema::table('recepciones', function (Blueprint $table) {
            $table->dropIndex(['resultado_validacion']);
            $table->dropColumn(['resultado_validacion', 'cantidad_recibida']);
        });
    }
};
