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
        Schema::table('empresas', function (Blueprint $table) {

            $table->after('vencimientoPago', function (Blueprint $table) {

                // $table->unsignedSmallInteger('idFormaPago')->default(1);
                // $table->boolean('pagoServicio')->default(false);
                
                // $table->boolean('imprimirSiNo')->default(true);
                // $table->unsignedSmallInteger('topeComprobantes')->default(10);
                
                $table->double('pagoMes')->default(0);
                $table->enum('mesas', ['si', 'no'])->default('no');
                $table->string('usuarioPago')->nullable();
                $table->string('comentario')->nullable();

            });
            
        
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('empresas', function (Blueprint $table) {
            // $table->dropColumn(['votes', 'avatar', 'location']);
            $table->dropColumn(['pagoMes', 'mesas', 'usuarioPago']);
        });
    }
};
