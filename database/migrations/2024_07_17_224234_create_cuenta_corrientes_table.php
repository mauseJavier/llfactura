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
        Schema::create('cuenta_corrientes', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('empresa_id'); //empresa_id
            $table->unsignedInteger('cliente_id'); //cliente_id
            $table->unsignedInteger('comprobante_id');  //comprobante_id
            $table->enum('tipo', ['venta', 'pago','interes']); //tipo venta-pago-interes para cuando se aplican intereces por mora  
            $table->string('comentario', length: 100); //comentario
            $table->double('debe'); //debe 
            $table->double('haber'); //haber
            $table->double('saldo'); //saldo 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_corrientes');
    }
};