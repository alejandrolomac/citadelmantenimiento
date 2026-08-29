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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rol')->nullable()->after('password');
            $table->tinyInteger('status')->default(1)->after('id_rol');
            $table->integer('failed_attempts')->default(0)->after('status');
            $table->string('foto')->nullable()->after('failed_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['id_rol', 'status', 'failed_attempts', 'foto']);
        });
    }
};
