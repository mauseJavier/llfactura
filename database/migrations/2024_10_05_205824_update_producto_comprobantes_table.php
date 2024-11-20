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

            $table->double('costo')->after('cantidad')->default(0);
            $table->string('marca')->after('proveedor')->default('General');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('producto_comprobantes', function (Blueprint $table) {

            $table->dropIfExists('costo');
            $table->dropIfExists('marca');

        });
    }
};
