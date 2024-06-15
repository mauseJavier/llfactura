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
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            
            $table->string('tipoComp', 15);

            $table->unsignedBigInteger('numero');
            $table->double('total');

            $table->unsignedBigInteger('cae');
            $table->date('fechaVencimiento');
            $table->dateTime('fecha');
            $table->integer('ptoVta');
            
            $table->integer('DocTipo');
            $table->unsignedBigInteger('cuitCliente');
            $table->string('razonSocial');
            $table->integer('tipoContribuyente');
            $table->string('domicilio')->nullable();
            
            $table->string('leyenda')->nullable();
            $table->integer('idFormaPago')->default(1);
            
            
            $table->unsignedBigInteger('empresa_id');
            
            $table->string('usuario')->nullable();
            
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};
