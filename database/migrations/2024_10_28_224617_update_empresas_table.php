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
        // CONFIGUARACION EMPRESA : 
        //     -   FORMA DE PAGO PREDETERMINADA EJ MERCADO PAGO -  
        //     -   pago de servicio ,
        //     - tope facturación importe  
        //     - OPCION PREDETERMINADA DE IMPRESION  TICKET 
        //     - Y IMPRECION O NO IMPRECION  
        //      (algo para las empresas gratis como tope de factura)
        Schema::table('empresas', function (Blueprint $table) {

            $table->after('vencimientoPago', function (Blueprint $table) {

                $table->unsignedSmallInteger('idFormaPago')->default(1);
                $table->boolean('pagoServicio')->default(false);
                $table->double('topeFacturacion')->default(0);
                $table->enum('formatoImprecion', ['T', 'A4'])->default('T');
                $table->boolean('imprimirSiNo')->default(true);
                $table->unsignedSmallInteger('topeComprobantes')->default(10);

            });
            
        
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            // $table->dropColumn(['votes', 'avatar', 'location']);
            $table->dropColumn(['idFormaPago', 'pagoServicio', 'topeFacturacion','formatoImprecion','imprimirSiNo','topeComprobantes']);
        });


    }
};
