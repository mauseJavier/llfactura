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
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('ingresosBrutos')->nullable();
            $table->string('telefonoNotificacion')->nullable();
            $table->string('instanciaWhatsapp')->nullable();
            $table->string('tokenWhatsapp')->nullable();
            // $table->boolean('ivaIncluido')->default(false);
            $table->enum('ivaIncluido', ['si', 'no'])->default('no')->after('ivaDefecto');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'ingresosBrutos',
                'telefonoNotificacion',
                'instanciaWhatsapp',
                'tokenWhatsapp',
                'ivaIncluido',
            ]);
        });
    }
};