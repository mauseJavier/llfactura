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

            // 'porcentaje'=> $value['porcentaje'],
            // 'precioLista'=> $value['precioLista'] ,
            // 'descuento'=> $value['descuento'] ,

            $table->double('porcentaje')->after('detalle')->default(0);
            $table->double('precioLista')->after('porcentaje')->default(0);
            $table->double('descuento')->after('precioLista')->default(0);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('producto_comprobantes', function (Blueprint $table) {

            $table->dropColumn('porcentaje');
            $table->dropColumn('precioLista');
            $table->dropColumn('descuento');

        });
    }
};
