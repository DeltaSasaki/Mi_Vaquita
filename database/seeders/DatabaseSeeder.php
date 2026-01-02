<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Importante: Asegúrate de importar el modelo User

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Primero llamamos a las categorías y productos para que se creen
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        // 2. Creamos el Usuario ADMINISTRADOR
        User::factory()->create([
            'name' => 'Admin Carnicero',
            'email' => 'admin@mivaquita.com',
            'password' => bcrypt('admin123'), // Contraseña encriptada
            'is_admin' => true, // <--- ESTO ES LO IMPORTANTE (Lo hace admin)
        ]);

        // 3. (Opcional) Creamos un cliente normal para pruebas
        User::factory()->create([
            'name' => 'Cliente Prueba',
            'email' => 'cliente@gmail.com',
            'password' => bcrypt('12345678'),
            'is_admin' => false,
        ]);
    }
}