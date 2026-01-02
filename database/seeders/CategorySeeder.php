<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Res',
                'slug' => 'res',
                'description' => 'Los mejores cortes de ganado vacuno, frescos y tiernos.',
                'image' => 'https://images.unsplash.com/photo-1546964124-0cce460f38ef?q=80&w=800&auto=format&fit=crop',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cerdo',
                'slug' => 'cerdo',
                'description' => 'Calidad premium en carne de porcino.',
                'image' => 'https://images.unsplash.com/photo-1602498456745-e9503b30470b?q=80&w=800&auto=format&fit=crop',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aves',
                'slug' => 'aves',
                'description' => 'Pollo y pavo fresco de granja.',
                'image' => 'https://images.unsplash.com/photo-1587593810167-a84920ea0781?q=80&w=800&auto=format&fit=crop',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Embutidos',
                'slug' => 'embutidos',
                'description' => 'Chorizos y salchichas artesanales.',
                'image' => 'https://images.unsplash.com/photo-1551028717-00163bba08ab?q=80&w=800&auto=format&fit=crop',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}