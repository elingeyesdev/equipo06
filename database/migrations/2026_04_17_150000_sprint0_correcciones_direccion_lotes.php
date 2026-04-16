<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sprint 0 correcciones: dirección tipificada, lotes 1-N con productos (idempotente).
     */
    public function up(): void
    {
        if (Schema::hasColumn('producers', 'address') && ! Schema::hasColumn('producers', 'address_type')) {
            Schema::table('producers', function (Blueprint $table) {
                $table->string('address_type', 30)->nullable()->after('email');
                $table->string('address_detail', 255)->nullable()->after('address_type');
            });

            foreach (DB::table('producers')->select('id', 'address')->cursor() as $row) {
                if (! empty($row->address)) {
                    DB::table('producers')->where('id', $row->id)->update([
                        'address_type' => 'otro',
                        'address_detail' => $row->address,
                    ]);
                }
            }

            Schema::table('producers', function (Blueprint $table) {
                $table->dropColumn('address');
            });
        }

        if (! Schema::hasColumn('productos', 'lote_id')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->foreignId('lote_id')->nullable()->after('activo')->constrained('lotes')->nullOnDelete();
            });
        }

        if (Schema::hasTable('lote_producto')) {
            $pivotRows = DB::table('lote_producto')->orderBy('id')->get();
            foreach ($pivotRows as $pivot) {
                DB::table('productos')
                    ->where('id', $pivot->producto_id)
                    ->whereNull('lote_id')
                    ->update(['lote_id' => $pivot->lote_id]);
            }
            Schema::dropIfExists('lote_producto');
        }

        if (! Schema::hasColumn('lotes', 'fecha_cosecha')) {
            Schema::table('lotes', function (Blueprint $table) {
                $table->string('nombre_lote', 120)->nullable()->after('codigo_lote');
                $table->date('fecha_cosecha')->nullable()->after('nombre_lote');
                $table->foreignId('productor_id')->nullable()->after('fecha_cosecha')->constrained('producers')->nullOnDelete();
                $table->text('descripcion')->nullable()->after('productor_id');
                $table->decimal('cantidad', 12, 3)->nullable()->after('descripcion');
                $table->string('tipo_producto', 40)->nullable()->after('cantidad');
            });
        } else {
            Schema::table('lotes', function (Blueprint $table) {
                if (! Schema::hasColumn('lotes', 'descripcion')) {
                    $table->text('descripcion')->nullable()->after('productor_id');
                }
                if (! Schema::hasColumn('lotes', 'cantidad')) {
                    $table->decimal('cantidad', 12, 3)->nullable()->after('descripcion');
                }
                if (! Schema::hasColumn('lotes', 'tipo_producto')) {
                    $table->string('tipo_producto', 40)->nullable()->after('cantidad');
                }
            });
        }

        foreach (DB::table('lotes')->orderBy('id')->cursor() as $lote) {
            $productos = DB::table('productos')->where('lote_id', $lote->id)->get();
            $first = $productos->first();
            $updates = [];
            if (empty($lote->fecha_cosecha) && ! empty($lote->fecha_creacion)) {
                $updates['fecha_cosecha'] = $lote->fecha_creacion;
            }
            if (empty($lote->productor_id) && $first) {
                $updates['productor_id'] = $first->productor_id;
            }
            if (empty($lote->tipo_producto) && $first) {
                $updates['tipo_producto'] = $first->tipo ?? 'otro';
            }
            if ($lote->cantidad === null && $productos->count() > 0) {
                $updates['cantidad'] = $productos->count();
            }
            if ($updates !== []) {
                DB::table('lotes')->where('id', $lote->id)->update($updates);
            }
        }

        if (Schema::hasColumn('lotes', 'fecha_creacion')) {
            Schema::table('lotes', function (Blueprint $table) {
                $table->dropColumn('fecha_creacion');
            });
        }

        $sm = DB::selectOne("SELECT sql FROM sqlite_master WHERE type='index' AND tbl_name='lotes' AND name LIKE '%codigo%'");
        if ($sm === null) {
            Schema::table('lotes', function (Blueprint $table) {
                $table->unique('codigo_lote');
            });
        }
    }

    public function down(): void
    {
        // Reversión parcial: solo si hace falta en entornos de desarrollo.
        if (Schema::hasColumn('lotes', 'fecha_cosecha') && ! Schema::hasColumn('lotes', 'fecha_creacion')) {
            Schema::table('lotes', function (Blueprint $table) {
                $table->date('fecha_creacion')->nullable()->after('codigo_lote');
            });
            foreach (DB::table('lotes')->cursor() as $l) {
                DB::table('lotes')->where('id', $l->id)->update([
                    'fecha_creacion' => $l->fecha_cosecha ?? now()->toDateString(),
                ]);
            }
        }

        if (Schema::hasColumn('lotes', 'productor_id')) {
            Schema::table('lotes', function (Blueprint $table) {
                try {
                    $table->dropUnique(['codigo_lote']);
                } catch (Throwable) {
                }
                $table->dropForeign(['productor_id']);
                $table->dropColumn([
                    'nombre_lote',
                    'fecha_cosecha',
                    'productor_id',
                    'descripcion',
                    'cantidad',
                    'tipo_producto',
                ]);
            });
        }

        if (! Schema::hasTable('lote_producto') && Schema::hasColumn('productos', 'lote_id')) {
            Schema::create('lote_producto', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lote_id')->constrained('lotes')->cascadeOnDelete();
                $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
                $table->unique(['lote_id', 'producto_id']);
            });
            foreach (DB::table('productos')->whereNotNull('lote_id')->cursor() as $p) {
                DB::table('lote_producto')->insertOrIgnore([
                    'lote_id' => $p->lote_id,
                    'producto_id' => $p->id,
                ]);
            }
            Schema::table('productos', function (Blueprint $table) {
                $table->dropForeign(['lote_id']);
                $table->dropColumn('lote_id');
            });
        }

        if (Schema::hasColumn('producers', 'address_type')) {
            Schema::table('producers', function (Blueprint $table) {
                $table->string('address')->nullable()->after('email');
            });
            foreach (DB::table('producers')->cursor() as $row) {
                if (! empty($row->address_detail)) {
                    DB::table('producers')->where('id', $row->id)->update(['address' => $row->address_detail]);
                }
            }
            Schema::table('producers', function (Blueprint $table) {
                $table->dropColumn(['address_type', 'address_detail']);
            });
        }
    }
};
