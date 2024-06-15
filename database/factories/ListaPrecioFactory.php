<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ListaPrecio>
 */
class ListaPrecioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->word(),
            'porcentaje' => fake()->randomFloat(2, 10, 100),
            'empresa_id'=>1,
        ];

                        // return [
        //     'codigo' => fake()->unique()->ean8,
        //     'nombre' => fake()->word(),
        //     'costo' => fake()->randomFloat(2, 1, 1000),
        //     'precio1' => fake()->randomFloat(2, 10, 100),
        //     'precio2' => fake()->randomFloat(2, 20, 200),
        //     'precio3' => fake()->randomFloat(2, 30, 300),
        //     'iva' => fake()->randomElement([21, 10.5]),
        //     'rubro' => fake()->randomElement(['General', 'Electrónica', 'Alimentación']),
        //     'proveedor' => fake()->randomElement(['General', 'Diarco', 'Vital','MAUSE']),
        //     'pesable' => fake()->randomElement(['si', 'no']),
        //     'empresa_id' => 1,
        // ];
        
    }
}
