<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'MAUSE LLFACTURA',
            'email' => 'mause.javi@gmail.com',
            'puntoVenta'=>4,
            'role_id'=> 3,
        ]);
        User::factory()->create([
            'name' => 'MARCE LLFACTURA',
            'email' => 'marce_nqn_19@hotmail.com',
            'puntoVenta'=>4,
            'role_id'=> 3,
        ]);

        $this->call([
            EmpresaSeeder::class,
            FormaPagoSeeder::class,
            RoleSeeder::class,
            InventarioSeeder::class,
            RubroSeeder::class,
            ProveedorSeeder::class,
            ListaPrecioSeeder::class,
            DepositoSeeder::class,

            SectorSeeder::class,

            MesaSeeder::class,

            // PostSeeder::class,
            // CommentSeeder::class,
        ]);

    }
}
