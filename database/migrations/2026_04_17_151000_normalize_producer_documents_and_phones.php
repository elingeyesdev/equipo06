<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Normaliza carnets a solo dígitos y teléfonos a +591XXXXXXXX cuando sea posible.
     */
    public function up(): void
    {
        foreach (DB::table('producers')->whereNotNull('document_number')->cursor() as $row) {
            $clean = preg_replace('/\D/', '', (string) $row->document_number) ?? '';
            if (strlen($clean) >= 5 && strlen($clean) <= 10) {
                DB::table('producers')->where('id', $row->id)->update(['document_number' => $clean]);
            } else {
                DB::table('producers')->where('id', $row->id)->update(['document_number' => null]);
            }
        }

        foreach (DB::table('producers')->whereNotNull('phone')->cursor() as $row) {
            $digits = preg_replace('/\D/', '', (string) $row->phone) ?? '';
            if (str_starts_with($digits, '591') && strlen($digits) >= 11) {
                $digits = substr($digits, 3);
            }
            if (strlen($digits) === 8) {
                DB::table('producers')->where('id', $row->id)->update(['phone' => '+591'.$digits]);
            }
        }
    }

    public function down(): void
    {
        // Sin reversión: datos ya normalizados.
    }
};
