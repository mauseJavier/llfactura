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
        //
        Schema::table('cierre_cajas', function (Blueprint $table) {

            $table->after('id', function (Blueprint $table) {

                // $table->unsignedSmallInteger('idFormaPago')->default(1);
                // $table->boolean('pagoServicio')->default(false);
                
                // $table->boolean('imprimirSiNo')->default(true);
                // $table->unsignedSmallInteger('topeComprobantes')->default(10);
                
                // $table->enum('activarPago2', ['si', 'no'])->default('no');

                $table->string('descripcion')->default('Cierre de caja');


            });
            
        
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('cierre_cajas', function (Blueprint $table) {
            // $table->dropColumn(['votes', 'avatar', 'location']);
            $table->dropColumn(['descripcion', ]);
        });
    }
};
