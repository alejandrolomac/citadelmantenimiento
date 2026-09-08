<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First we add a new column 'adjuntos'
        Schema::table('agendas', function (Blueprint $table) {
            $table->text('adjuntos')->nullable()->after('estado');
        });

        // Migrate existing 'adjunto' data to 'adjuntos' if needed
        $agendas = DB::table('agendas')->whereNotNull('adjunto')->get();
        foreach ($agendas as $agenda) {
            if (!empty($agenda->adjunto)) {
                // We store the single adjunto as an array encoded in json
                DB::table('agendas')->where('id', $agenda->id)->update([
                    'adjuntos' => json_encode([$agenda->adjunto])
                ]);
            }
        }

        // We can safely drop the old column 'adjunto'
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropColumn('adjunto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->string('adjunto')->nullable()->after('estado');
        });

        $agendas = DB::table('agendas')->whereNotNull('adjuntos')->get();
        foreach ($agendas as $agenda) {
            if (!empty($agenda->adjuntos)) {
                $archivos = json_decode($agenda->adjuntos, true);
                if (is_array($archivos) && count($archivos) > 0) {
                    DB::table('agendas')->where('id', $agenda->id)->update([
                        'adjunto' => $archivos[0] // take only the first one back
                    ]);
                }
            }
        }

        Schema::table('agendas', function (Blueprint $table) {
            $table->dropColumn('adjuntos');
        });
    }
};
