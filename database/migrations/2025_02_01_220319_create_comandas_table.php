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
        Schema::create('comandas', function (Blueprint $table) {
            $table->id();

            $table->string('nombreCliente')->nullable()->default('Cliente');

            $table->unsignedInteger('numeroMesa'); //empresa_id
            $table->string('nombreMesa');
            $table->string('nombreMesero');
            $table->string('estado')->nullable();


            $table->json('comanda');



            $table->unsignedInteger('empresa_id'); //empresa_id

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comandas');
    }
};
