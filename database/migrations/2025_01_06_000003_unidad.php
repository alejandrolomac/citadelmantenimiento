<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//------ Tabla Tipo de Mantenimiento ------//
return new class extends Migration
{
    public function up()
    {
        Schema::create('unidad', function (Blueprint $table) {
            $table->id('id_unidad');
            $table->string('tipo_vehiculo', 50)->nullable();
            $table->string('codigo_vehiculo', 100)->nullable();
            $table->string('kilometraje', 50)->nullable();
            $table->string('ubicacion_unidad', 100)->nullable();
            $table->string('folio', 50)->nullable();
            $table->boolean('estado')->default(true);

            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
        });
    }

    public function down()
    {
        Schema::dropIfExists('unidad');
    }
};

