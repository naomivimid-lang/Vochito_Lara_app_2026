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
        $this->call(ProductoSeeder::class);
        
        // Usuarios de prueba para la demostración (contraseña de ejemplo, no usar en producción)
        User::factory()->create([
            'name' => 'Administrador Bochito',
            'email' => 'admin@bochito.test',
            'password' => 'bochito2026',
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Cliente de prueba',
            'email' => 'cliente@bochito.test',
            'password' => 'bochito2026',
        ]);
    }
}
