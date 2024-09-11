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
        Schema::create('envio_stocks', function (Blueprint $table) {
            $table->id();

            $table->string('codigo');
            $table->string('detalle');
            $table->unsignedInteger('depositoOrigen_id');
            $table->unsignedInteger('depositoDestino_id');
            $table->double('stock');
            $table->enum('estado', ['enviado', 'recibido']);
            $table->string('comentario');
            $table->string('usuarioEnvio',100);
            $table->string('usuarioRecibo',100)->nullable();
            $table->unsignedInteger('empresa_id');
            $table->unsignedInteger('eliminarIdStock'); //PARA CUANDO ELIMINAS EL ENVIO


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('envio_stocks');
    }
};
