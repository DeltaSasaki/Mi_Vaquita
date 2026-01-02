<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order; // <--- Importante importar esto

class AdminController extends Controller
{
    public function index()
    {
        // 1. Contadores básicos
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalUsers = User::where('is_admin', false)->count();

        // 2. DATOS DE NEGOCIO (Nuevos)
        // Ganancias: Suma de todo lo que no esté cancelado
        $totalEarnings = Order::where('status', '!=', 'cancelado')->sum('total');
        
        // Pendientes: Pedidos que requieren atención inmediata
        $pendingOrders = Order::where('status', 'pendiente')->count();

        // 3. Tabla Rápida: Los últimos 5 pedidos
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalCategories', 
            'totalUsers', 
            'totalEarnings', 
            'pendingOrders', 
            'recentOrders'
        ));
    }
}