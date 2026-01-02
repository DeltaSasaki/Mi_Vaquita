<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
    {
        // 1. Buscamos la categoría por su slug (ej: 'res')
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // 2. Cargamos los productos de esa categoría
        $products = $category->products()->where('is_active', true)->get();

        // 3. Retornamos la vista (que crearemos ahora)
        return view('category.show', compact('category', 'products'));
    }
}