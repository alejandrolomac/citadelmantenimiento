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
        Schema::table('orden_trabajo', function (Blueprint $table) {
            $table->time('hora_inicio')->nullable()->change();
            $table->time('hora_final')->nullable()->change();
            $table->string('kilometraje')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orden_trabajo', function (Blueprint $table) {
            $table->time('hora_inicio')->nullable(false)->change();
            $table->time('hora_final')->nullable(false)->change();
            $table->string('kilometraje')->nullable(false)->change();
        });
    }
};
