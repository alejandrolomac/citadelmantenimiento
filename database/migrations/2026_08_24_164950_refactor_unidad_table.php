<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('unidad', 'nombre')) {
            Schema::table('unidad', function (Blueprint $table) {
                $table->string('nombre')->nullable()->after('id_unidad');
                $table->string('tb_id')->nullable()->after('nombre');
                $table->date('fecha')->nullable()->after('tb_id');
                $table->string('type')->nullable()->after('fecha');
            });
        }
        
        // Copiar datos existentes
        \Illuminate\Support\Facades\DB::statement('UPDATE unidad SET nombre = codigo_vehiculo');

        Schema::table('unidad', function (Blueprint $table) {
            $colsToDrop = ['tipo_vehiculo', 'codigo_vehiculo', 'kilometraje', 'ubicacion_unidad', 'folio', 'vin'];
            foreach ($colsToDrop as $col) {
                if (Schema::hasColumn('unidad', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unidad', function (Blueprint $table) {
            $table->string('tipo_vehiculo', 50)->nullable();
            $table->string('codigo_vehiculo', 100)->nullable();
            $table->string('kilometraje', 50)->nullable();
            $table->string('ubicacion_unidad', 100)->nullable();
            $table->string('folio', 50)->nullable();
            $table->string('vin', 50)->nullable();
            
            $table->dropColumn(['nombre', 'tb_id', 'fecha', 'type']);
        });
    }
};
