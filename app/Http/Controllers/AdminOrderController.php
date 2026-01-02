<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    // 1. Ver lista de todos los pedidos
    public function index()
    {
        // Traemos los pedidos ordenados del más nuevo al más viejo
        // Usamos 'with' para traer los datos del usuario de una vez (optimización)
        $orders = Order::with('user')->latest()->paginate(10);
        
        return view('admin.orders.index', compact('orders'));
    }

    // 2. Ver detalle de un solo pedido (Qué productos compró)
    public function show($id)
    {
        // Traemos la orden con sus items (productos) y el usuario
        $order = Order::with(['items', 'user'])->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }

    // 3. Cambiar el estado del pedido
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pendiente,completado,cancelado'
        ]);

        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'El estado del pedido ha sido actualizado.');
    }
}