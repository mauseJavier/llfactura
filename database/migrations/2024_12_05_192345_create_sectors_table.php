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
        Schema::create('sectors', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('numero')->default(1); //empresa_id
            $table->string('nombre')->default('Sector');            
            $table->integer('capacidad')->default(1);            
            $table->string('titular')->nullable();

            $table->enum('estado', ['activo', 'cancelado'])->default('activo');

            $table->string('usuario')->nullable();
            
            $table->unsignedBigInteger('empresa_id')->default(1);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sectors');
    }
};
