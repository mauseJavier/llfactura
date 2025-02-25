<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mesa>
 */
class MesaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'numero' => fake()->randomNumber(3, false),
            'nombre' => fake()->randomElement([
                "Meza Principal",
                "Meza Secundaria",
                "Meza Otra",
 
            ]),
            'capacidad' => fake()->randomNumber(2),
            'razonSocial' => fake()->randomElement([
                "Marce",
                "Otro",
                "llFactura",
 
            ]),          
            'sector' => fake()->randomElement([
                1,
                2,
                3,
 
            ]),      
            
        ];
    }
}
