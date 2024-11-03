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
        Schema::create('novedad', function (Blueprint $table) {
            $table->id();

            $table->string('titulo', length: 100);
            $table->string('detalle');
            $table->string('nombreRuta')->nullable();
            $table->string('url')->nullable();
            $table->string('pie', length: 100)->nullable();
            $table->string('usuario', length: 100);



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novedad');
    }
};
