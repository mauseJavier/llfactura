<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('empresas')->insert([
            'razonSocial' => 'Empresa Prueba',
            'cuit' => '20080202874',
            'domicilio' => 'Domicilio',
            'fe'=>'no',
            'titular'=>'Titular Prueba',
        ]);

        DB::table('empresas')->insert([
            'razonSocial' => 'HOTEL KINGS CROWN',
            'cuit' => '20080202874',
            'domicilio' => 'Av. del Trabajo 540 Las Lajas',
            'fe'=>'si',
            'titular'=>'Gimenez Teodoro',
        ]);
    }
}
