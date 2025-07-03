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
            $table->boolean('articuloDestacado')->default(false)->after('controlStock');
            $table->boolean('publicarTienda')->default(false)->after('articuloDestacado');
            $table->text('comentarioTienda')->nullable()->after('publicarTienda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventarios', function (Blueprint $table) {
            $table->dropColumn(['articuloDestacado', 'publicarTienda', 'comentarioTienda']);
        });
    }
};
