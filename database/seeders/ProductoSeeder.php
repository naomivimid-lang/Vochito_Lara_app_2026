<?php

namespace Database\Seeders;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Crea los productos del catálogo de tiendas locales y un pedido para cada uno.
     */
    public function run(): void
    {
        $productos = [
            ['Pan artesanal', 80, 'Pan de horno de leña hecho cada mañana. Panadería La Espiga.'],
            ['Tortillas de maíz (1 kg)', 28, 'Maíz nixtamalizado de la región. Tortillería Doña Lupe.'],
            ['Miel de abeja (500 g)', 120, 'Miel pura de productores locales. Apiarios Sierra Verde.'],
            ['Jitomate (1 kg)', 32, 'Cosechado en huertos de la comunidad. Verduras Hernández.'],
            ['Naranja (costal 5 kg)', 95, 'Naranja dulce para jugo. Frutería Don Chuy.'],
            ['Aguacate (1 kg)', 70, 'Aguacate hass de temporada. Frutería Don Chuy.'],
            ['Canasta de palma', 150, 'Tejida a mano por artesanas locales. Artesanías Raíces.'],
            ['Jarro de barro', 65, 'Barro natural, ideal para café de olla. Alfarería El Comal.'],
            ['Huacal de madera', 110, 'Para transportar o exhibir mercancía. Carpintería San José.'],
            ['Jabón de avena', 45, 'Jabón artesanal sin químicos agresivos. Cooperativa Sierra Verde.'],
        ];

        $clientes = [
            'María López', 'José Hernández', 'Guadalupe Martínez', 'Juan Pérez', 'Ana Sánchez',
            'Luis Ramírez', 'Rosa Flores', 'Carlos Torres', 'Elena Gómez', 'Miguel Díaz',
        ];

        foreach ($productos as $i => [$nombre, $precio, $descripcion]) {
            $producto = Producto::create([
                'nombre'      => $nombre,
                'descripcion' => $descripcion,
                'precio'      => $precio,
                'stock'       => fake()->numberBetween(5, 40),
                'activo'      => true,
            ]);

            Pedido::create([
                'cliente'     => $clientes[$i],
                'producto_id' => $producto->id,
                'cantidad'    => fake()->numberBetween(1, 5),
                'entrega'     => now()->addDays(fake()->numberBetween(1, 10))->setTime(fake()->numberBetween(9, 18), 0),
                'estatus'     => fake()->randomElement(Pedido::ESTATUS),
            ]);
        }
    }
}
