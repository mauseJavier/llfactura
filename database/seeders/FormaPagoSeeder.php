<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use App\Models\FormaPago;
    

class FormaPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('forma_pagos')->insert([
            'nombre' => 'Efectivo',
            'comentario' => 'sin comentarios',
        ]);

        FormaPago::create([
            'nombre'=>'Tarjeta',
            
        ]);

        FormaPago::create([
            'nombre'=>'Mercado Pago',
            
        ]);
    
    }
}
