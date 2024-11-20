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
        Schema::table('inventarios', function (Blueprint $table) {
            // $table->double('porcentaje')->default(0)->after('precio3');

            $table->boolean('favorito')->default(false)->after('empresa_id');

            // $table->enum('favorito', ['true', 'false'])->default('false')->after('empresa_id');
            // $table->enum('estado', ['true', 'false'])->default('true')->after('favorito');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventarios', function (Blueprint $table) {
            // $table->dropColumn('porcentaje');
            $table->dropColumn('favorito');

            // Añade otras reversión aquí
        });
    }
};
