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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('razonSocial');
            $table->unsignedBigInteger('cuit');
            $table->string('claveFiscal')->nullable();

            $table->string('domicilio');
            $table->enum('fe', ['si', 'no']);
            $table->enum('iva', ['ME', 'RI'])->default('ME');
            $table->double('ivaDefecto')->default(21);

            $table->double('precio2')->default(50);
            $table->double('precio3')->default(100);


            $table->date('inicioActividades')->default(date('Y-m-d H:i:s'));
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->string('titular');
            $table->string('logo')->nullable();
            $table->date('vencimientoPago')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};


// RECEPTOR
// EMISOR	 	Consumidor final / Exento	Monotributista	Responsable inscripto	Exportación	Turista del extranjero
//             Responsable inscripto	B	A	A	E	B o T
//             Monotributista / Exento	C	C	C	E	C