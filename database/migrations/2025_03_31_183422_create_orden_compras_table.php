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
        Schema::create('orden_compras', function (Blueprint $table) {
            $table->id();
            $table->string('numeroDeOrden')->nullable();
            $table->date('fecha')->nullable();
            $table->string('empresa')->nullable();
            $table->unsignedBigInteger('idProveedor')->nullable();
            $table->string('proveedor');
            $table->string('cuit_proveedor')->nullable();
            $table->string('direccion_proveedor')->nullable();
            $table->string('email_proveedor')->nullable();
            $table->string('telefono_proveedor')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('iva', 10, 2);
            $table->decimal('total', 10, 2);
            
            $table->unsignedBigInteger('empresa_id');
            $table->string('usuario')->nullable();
            $table->unsignedBigInteger('usuario_id')->nullable();

            $table->string('estado')->default('pendiente'); // Estado de la orden de compra
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_compras');
    }
};



