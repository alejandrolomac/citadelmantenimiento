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
        Schema::table('unidad', function (Blueprint $table) {
            $table->foreignId('tipo_dispositivo_id')->nullable()->constrained('tipo_dispositivos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unidad', function (Blueprint $table) {
            $table->dropForeign(['tipo_dispositivo_id']);
            $table->dropColumn('tipo_dispositivo_id');
        });
    }
};
