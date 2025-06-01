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
        Schema::create('articulo_ordens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordenCompraId')->constrained('orden_compras')->onDelete('cascade');
            $table->string('codigo');
            $table->string('detalle');

            $table->string('rubro');
            $table->string('proveedor');
            $table->string('marca');

            $table->integer('cantidad');
            $table->decimal('costoUnitario', 10, 2);
            $table->decimal('subTotal', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulo_ordens');
    }
};



