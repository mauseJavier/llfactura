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
        Schema::table('producto_comprobantes', function (Blueprint $table) {

            $table->integer('idFormaPago2')->after('idFormaPago')->default(1);



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('producto_comprobantes', function (Blueprint $table) {

            $table->dropColumn('idFormaPago2');


        });
    }
};
