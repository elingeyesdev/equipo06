<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * ENT 1.4: coherencia de datos heredados (productor, tipo, lotes vacíos).
     */
    public function up(): void
    {
        DB::table('lotes')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw('1'))
                    ->from('productos')
                    ->whereColumn('productos.lote_id', 'lotes.id');
            })
            ->delete();

        foreach (DB::table('lotes')->orderBy('id')->cursor() as $lote) {
            $primerProducto = DB::table('productos')
                ->where('lote_id', $lote->id)
                ->orderBy('id')
                ->first();

            $updates = [];

            if ($primerProducto) {
                if (empty($lote->productor_id)) {
                    $updates['productor_id'] = $primerProducto->productor_id;
                }
                if (empty($lote->tipo_producto) && ! empty($primerProducto->tipo)) {
                    $updates['tipo_producto'] = $primerProducto->tipo;
                }
            }

            if (empty($lote->fecha_cosecha)) {
                $updates['fecha_cosecha'] = now()->toDateString();
            }

            $nProductos = DB::table('productos')->where('lote_id', $lote->id)->count();
            if (($lote->cantidad === null || (float) $lote->cantidad <= 0) && $nProductos > 0) {
                $updates['cantidad'] = max(1, $nProductos);
            }

            if ($updates !== []) {
                DB::table('lotes')->where('id', $lote->id)->update($updates);
            }
        }

        foreach (DB::table('lotes')->whereNull('productor_id')->orderBy('id')->cursor() as $lote) {
            $pid = DB::table('productos')->where('lote_id', $lote->id)->value('productor_id');
            if ($pid) {
                DB::table('lotes')->where('id', $lote->id)->update(['productor_id' => $pid]);
            }
        }

        DB::table('lotes')->whereNull('productor_id')->delete();
    }

    public function down(): void
    {
        // Irreversible: datos ya fusionados o eliminados.
    }
};
