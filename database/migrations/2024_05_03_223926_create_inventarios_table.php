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
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();

            $table->string('codigo');
            $table->string('detalle');
            $table->double('costo')->default(0);
            $table->double('precio1')->default(0);
            $table->double('precio2')->default(0);
            $table->double('precio3')->default(0);
            $table->double('iva')->default(21);
            $table->string('rubro')->default('General')->nullable();
            $table->string('proveedor')->default('General')->nullable();
            $table->enum('pesable', ['si', 'no'])->default('no');
            $table->enum('controlStock', ['si', 'no'])->default('no');
            $table->string('imagen')->nullable();
            $table->unsignedInteger('empresa_id')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
