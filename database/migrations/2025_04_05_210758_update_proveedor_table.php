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
        Schema::table('proveedors', function (Blueprint $table) {

            // 'porcentaje'=> $value['porcentaje'],
            // 'precioLista'=> $value['precioLista'] ,
            // 'descuento'=> $value['descuento'] ,

            // $table->double('costo')->after('cantidad')->default(0);
            $table->string('domicilio')->after('cuit')->default('Sin domicilio')->nullable();
            $table->string('telefono')->after('domicilio')->default('Sin telefono')->nullable();
            $table->string('email')->after('telefono')->default('Sin email')->nullable();



            // $table->timestamps();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('proveedors', function (Blueprint $table) {

            // $table->dropIfExists('costo');
            // $table->dropIfExists('marca');
            $table->dropColumn('domicilio'); // Elimina la columna si se revierte la migración
            $table->dropColumn('telefono'); // Elimina la columna si se revierte la migración
            $table->dropColumn('email'); // Elimina la columna si se revierte la migración



        });
    }
};
