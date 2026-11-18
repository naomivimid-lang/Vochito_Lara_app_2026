<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre'      => fake()->unique()->words(2, true),
            'descripcion' => fake()->sentence(),
            'precio'      => fake()->randomFloat(2, 50, 2000),
            'stock'       => fake()->numberBetween(0, 50),
            'activo'      => true,
        ];
    }
}
