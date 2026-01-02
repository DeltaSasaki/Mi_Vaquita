<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Coupon;

class CartController extends Controller
{
    // 1. Añadir al carrito (CORREGIDO PARA ACEPTAR CANTIDAD)
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // Capturamos la cantidad que viene del formulario (si no viene nada, asume 1)
        $qtyToAdd = (int) $request->input('quantity', 1);

        // Nos aseguramos de que sea al menos 1
        if($qtyToAdd < 1) $qtyToAdd = 1;

        if(isset($cart[$id])) {
            // Si ya existe, SUMAMOS la cantidad nueva a la que ya tenía
            $cart[$id]['quantity'] += $qtyToAdd;
        } else {
            // Si es nuevo, lo creamos con la cantidad solicitada
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => $qtyToAdd,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

        // Si ya hay un cupón aplicado, lo recalculamos
        if(session()->has('coupon')) {
            $this->recalculateCouponInSession($cart);
        }

        // Calcular total de items para el contador del menú
        $totalQuantity = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success' => true,
            'message' => '¡' . $product->name . ' agregado (' . $qtyToAdd . ' Kg)!',
            'cartCount' => $totalQuantity
        ]);
    }
    
    // 2. Ver carrito
    public function index()
    {
        return view('cart.index');
    }
    
    // 3. Actualizar cantidad
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            
            // Actualizamos la cantidad
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            
            // 1. Recalculamos SUB-TOTAL de ese producto
            $itemSubtotal = $cart[$request->id]["quantity"] * $cart[$request->id]["price"];
            
            // 2. Recalculamos el TOTAL BRUTO
            $cartTotal = 0;
            foreach($cart as $item) {
                $cartTotal += $item['price'] * $item['quantity'];
            }

            // 3. Recalculamos el CUPÓN si existe
            $discount = 0;
            if(session()->has('coupon')) {
                $discount = $this->recalculateCouponInSession($cart);
            }

            // 4. Calculamos el TOTAL FINAL a pagar
            $finalTotal = max(0, $cartTotal - $discount);

            return response()->json([
                'success' => true,
                'subtotal' => number_format($itemSubtotal, 2),
                'total' => number_format($finalTotal, 2)
            ]);
        }
    }

    // 4. Eliminar producto
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }

            if(count($cart) > 0 && session()->has('coupon')) {
                $this->recalculateCouponInSession($cart);
            } elseif (count($cart) == 0) {
                session()->forget('coupon');
            }

            return response()->json(['success' => true]);
        }
    }

    // 5. Aplicar Cupón
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return back()->with('error', 'El cupón no es válido o ha expirado.');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Agrega productos al carrito primero.');
        }

        $discount = $coupon->calculateDiscount($cart);

        if ($discount <= 0) {
            return back()->with('error', 'Este cupón no aplica a los productos de tu carrito.');
        }

        session()->put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $discount,
            'type' => $coupon->type
        ]);

        return back()->with('success', '¡Cupón aplicado correctamente!');
    }

    // 6. Remover Cupón
    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Cupón eliminado.');
    }

    // Función auxiliar privada
    private function recalculateCouponInSession($cart)
    {
        $sessionCoupon = session('coupon');
        $couponModel = Coupon::find($sessionCoupon['id']);

        if ($couponModel && $couponModel->isValid()) {
            $newDiscount = $couponModel->calculateDiscount($cart);
            
            session()->put('coupon', [
                'id' => $couponModel->id,
                'code' => $couponModel->code,
                'discount' => $newDiscount,
                'type' => $couponModel->type
            ]);

            return $newDiscount;
        } else {
            session()->forget('coupon');
            return 0;
        }
    }
}