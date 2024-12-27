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
        Schema::table('mesas', function (Blueprint $table) {

            // $table->double('importeUno')->after('idFormaPago')->default(0);            
            // $table->double('importeDos')->after('idFormaPago2')->default(0);
            
            $table->integer('cantidadComenzales')->default(1);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('mesas', function (Blueprint $table) {
            // $table->dropColumn('idFormaPago2');
            // $table->dropColumn('importeUno');
            $table->dropColumn('cantidadComenzales');

        });
    }
};
