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
        Schema::create('mesas', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('numero')->default(1); //empresa_id
            $table->string('nombre')->default('Mesa');
            $table->integer('capacidad')->default(1);
          
            

            $table->integer('tipoDocumento')->default(99);
            $table->integer('tipoContribuyente')->default(5);
            $table->unsignedBigInteger('numeroDocumento')->default(0);
            $table->string('razonSocial')->nullable();
            $table->string('domicilio')->nullable();
            $table->string('correo')->nullable();

            $table->string('comentario')->nullable();

            $table->double('total')->default(0);


            $table->enum('estado', ['activa', 'cancelada'])->default('activa');

            $table->string('usuario')->nullable();

            
            $table->json('data')->nullable(); // Campo para guardar el array o objeto
            
            
            $table->unsignedBigInteger('sector')->default(1);

            $table->unsignedBigInteger('empresa_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};
