<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product; // <--- ¡Importante! No olvides esta línea

class HomeController extends Controller
{
    public function index()
    {
        // 1. Traemos categorías activas
        $categories = Category::where('is_active', true)->get();

        // 2. Traemos productos activos (limitamos a 8 para no saturar la home)
        $products = Product::where('is_active', true)->take(8)->get();

        // 3. Enviamos ambas variables a la vista
        return view('welcome', compact('categories', 'products'));
    }
}