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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();

            $table->string('codigo');
            $table->string('detalle');
            $table->unsignedInteger('deposito_id');
            $table->double('stock');
            $table->string('comentario');
            $table->string('usuario',100);
            $table->unsignedInteger('empresa_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
