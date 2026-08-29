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
        Schema::create('tipo_dispositivo_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_dispositivo_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('tipo_dispositivo_id')->references('id')->on('tipo_dispositivos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_dispositivo_user');
    }
};
