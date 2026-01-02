<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;

class CheckoutController extends Controller
{
    public function index()
    {
        // 1. Verificar si hay carrito
        $cart = session()->get('cart');
        if(!$cart || count($cart) == 0) {
            return redirect()->route('home')->with('error', 'Tu carrito está vacío.');
        }

        // 2. Calcular totales iniciales
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // 3. Verificar si hay cupón aplicado
        $discount = 0;
        if (session()->has('coupon')) {
            $couponSession = session('coupon');
            $coupon = Coupon::find($couponSession['id']);
            
            if ($coupon && $coupon->isValid()) {
                 // Recalculamos por seguridad
                 $discount = $coupon->calculateDiscount($cart);
            } else {
                // Si venció mientras navegaba, lo quitamos
                session()->forget('coupon');
            }
        }

        $total = max(0, $subtotal - $discount);
        $user = Auth::user();

        return view('checkout.index', compact('cart', 'subtotal', 'discount', 'total', 'user'));
    }

    public function success($id)
    {
        return view('checkout.success', ['orderId' => $id]);
    }
}