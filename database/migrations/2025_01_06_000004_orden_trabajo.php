<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//------ Tabla Orden de Trabajo ------//
return new class extends Migration
{
    public function up()
    {
        Schema::create('orden_trabajo', function (Blueprint $table) {
            $table->id('id_orden_trabajo');
            $table->foreignId('id_usuario')->constrained('users', 'id')->nullable();
            $table->foreignId('id_unidad')->constrained('unidad', 'id_unidad');
            $table->string('no_orden', 50)->nullable();
            $table->date('fecha');
            $table->string('hora_inicio', 15);
            $table->string('hora_final', 15)->nullable();
            $table->string('tipo_mantenimiento', 50);
            $table->string('kilometraje', 50)->nullable();
            $table->string('conductor', 50)->nullable();
            $table->json('formulario');

            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
        });
    }

    public function down()
    {
        Schema::dropIfExists('orden_trabajo');
    }
};