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
        Schema::create('tipo_dispositivo_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_dispositivo_id')->constrained('tipo_dispositivos')->onDelete('cascade');
            $table->string('nombre_original');
            $table->string('ruta_archivo');
            $table->string('tipo_archivo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_dispositivo_archivos');
    }
};
