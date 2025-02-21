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
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();

            $table->string('tipo')->nullable()->default('Gasto');

            $table->double('importe');

            $table->string('formaPago')->default('Efectivo');

            $table->enum('estado', ['Pago', 'Impago']);


            $table->unsignedInteger('idProveedor')->nullable(); //empresa_id


            $table->string('comentario')->nullable();

            $table->date('fechaNotificacion')->nullable();

            $table->string('usuario');


            $table->unsignedInteger('empresa_id'); //empresa_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
