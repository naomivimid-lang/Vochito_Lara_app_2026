<?php

namespace Database\Seeders;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Crea 10 productos y un pedido para cada uno.
     */
    public function run(): void
    {
        Producto::factory(10)->create()->each(function ($producto) {
            Pedido::create([
                'cliente'     => fake()->name(),
                'producto_id' => $producto->id,
                'cantidad'    => fake()->numberBetween(1, 5),
                'entrega'     => now()->addDays(fake()->numberBetween(1, 10)),
            ]);
        });
    }
}
