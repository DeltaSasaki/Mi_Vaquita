<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        // Si no escribe nada, lo mandamos al inicio
        if(!$query){ return redirect()->route('home'); }

        // Buscamos en nombre O descripción (LIKE es para buscar coincidencias parciales)
        $products = Product::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->with('category')
            ->get();

        return view('search.results', compact('products', 'query'));
    }
}