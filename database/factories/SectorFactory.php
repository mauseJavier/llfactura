<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sector>
 */
class SectorFactory extends Factory
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
                "Sector Principal",
                "Sector Secundaria",
                "Sector Otra",
 
            ]),
            'capacidad' => fake()->randomNumber(2),
            'titular' => fake()->randomElement([
                "Titular Sector Marce",
                "Titular Sector Otro",
                "Titular Sector llFactura",
 
            ]),            
            
        ];
    }
}
