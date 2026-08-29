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
            $table->string('tecnico')->nullable()->after('conductor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orden_trabajo', function (Blueprint $table) {
            $table->dropColumn('tecnico');
        });
    }
};
