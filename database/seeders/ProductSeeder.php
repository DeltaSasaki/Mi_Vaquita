<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Obtenemos los IDs de las categorías para asignarlos correctamente
        // Nota: Asumimos que corriste el CategorySeeder antes
        $resId = Category::where('slug', 'res')->first()->id ?? 1;
        $cerdoId = Category::where('slug', 'cerdo')->first()->id ?? 2;
        $avesId = Category::where('slug', 'aves')->first()->id ?? 3;

        $products = [
            [
                'category_id' => $resId,
                'name' => 'T-Bone Steak',
                'slug' => 't-bone-steak',
                'description' => 'Corte premium de res con hueso en forma de T, ideal para asados.',
                'price' => 15.00,
                'stock' => 20,
                'image' => 'https://images.unsplash.com/photo-1600891964092-4316c288032e?q=80&w=800&auto=format&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $resId,
                'name' => 'Lomo Fino',
                'slug' => 'lomo-fino',
                'description' => 'La parte más tierna de la res, perfecta para medallones.',
                'price' => 18.50,
                'stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1558030006-450675393462?q=80&w=800&auto=format&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $cerdoId,
                'name' => 'Costillas BBQ',
                'slug' => 'costillas-bbq',
                'description' => 'Costillar de cerdo carnoso, especial para hornear o parrilla.',
                'price' => 10.50,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1615937651188-4b92cd380284?q=80&w=800&auto=format&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $avesId,
                'name' => 'Pollo Entero',
                'slug' => 'pollo-entero',
                'description' => 'Pollo fresco de campo, sin menudencias.',
                'price' => 5.20,
                'stock' => 50,
                'image' => 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?q=80&w=800&auto=format&fit=crop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('products')->insert($products);
    }
}