<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recepciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('envio_id')
                ->unique()
                ->constrained('envios')
                ->cascadeOnDelete();
            $table->dateTime('fecha_llegada');
            $table->string('estado_entrega', 30)->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recepciones');
    }
};
