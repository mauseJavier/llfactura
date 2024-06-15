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
        Schema::create('producto_comprobantes', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedInteger('comprobante_id');
            $table->integer('comprobante_numero');

            $table->string('codigo');
            $table->string('detalle');
            $table->double('precio');
            $table->double('iva');
            $table->double('cantidad');
            $table->string('rubro');
            $table->string('proveedor');

            $table->date('fecha');
            $table->string('tipoComp', 15); 
            $table->integer('idFormaPago')->default(1);
            $table->integer('ptoVta');
            $table->string('usuario')->nullable();
            $table->unsignedBigInteger('empresa_id');


            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_comprobantes');
    }
};
