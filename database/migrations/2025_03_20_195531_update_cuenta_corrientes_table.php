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
        Schema::table('cuenta_corrientes', function (Blueprint $table) {

            // 'porcentaje'=> $value['porcentaje'],
            // 'precioLista'=> $value['precioLista'] ,
            // 'descuento'=> $value['descuento'] ,

            // $table->double('costo')->after('cantidad')->default(0);
            $table->string('formaPago')->after('tipo')->default('Efectivo');

            // $table->timestamps();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('cuenta_corrientes', function (Blueprint $table) {

            // $table->dropIfExists('costo');
            // $table->dropIfExists('marca');
            $table->dropColumn('formaPago'); // Elimina la columna si se revierte la migración



        });
    }
};
