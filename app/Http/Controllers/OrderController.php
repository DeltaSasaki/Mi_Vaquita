<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validar que el carrito no esté vacío
        $cart = session()->get('cart');
        if(!$cart) {
            return back()->with('error', 'El carrito está vacío.');
        }

        // 2. VALIDACIÓN DE PAGO (Aquí está la lógica nueva)
        $request->validate([
            'payment_method' => 'required|in:bs_efectivo,divisa_efectivo,pago_movil,zelle,punto',
            // La referencia es obligatoria SOLO SI el método es pago_movil o zelle
            'payment_reference' => 'required_if:payment_method,pago_movil,zelle', 
            // Validaciones de envío
            'shipping_type' => 'required|in:pickup,delivery',
            'address' => 'required_if:shipping_type,delivery',
        ], [
            'payment_reference.required_if' => 'Por favor ingresa el número de referencia o comprobante.'
        ]);

        // 3. Obtener el usuario autenticado (SOLUCIÓN A TU ERROR)
        $user = Auth::user(); 

        // 3.1 Recalcular Total y Descuentos (SEGURIDAD)
        $calculatedTotal = 0;
        foreach($cart as $item) {
            $calculatedTotal += $item['price'] * $item['quantity'];
        }

        $discountAmount = 0;
        $couponId = null;
        $couponCode = null;

        if (session()->has('coupon')) {
            $couponSession = session('coupon');
            // Verificamos de nuevo la validez del cupón al momento de la compra
            $coupon = Coupon::find($couponSession['id']);
            
            if ($coupon && $coupon->isValid()) {
                 $discountAmount = $coupon->calculateDiscount($cart);
                 $couponId = $coupon->id;
                 $couponCode = $coupon->code;
            }
        }

        $finalTotal = max(0, $calculatedTotal - $discountAmount);

        // 4. Iniciar Transacción
        DB::beginTransaction();

        try {
            // Crear la Orden
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pendiente',
                
                // Datos de Pago
                'payment_method' => $request->payment_method,
                'payment_reference' => $request->payment_reference,
                
                // Datos de Envío
                'shipping_type' => $request->shipping_type,
                'address' => $request->shipping_type == 'delivery' ? $request->address : 'Retiro en Tienda',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,

                'total' => $finalTotal, // Usamos el total calculado en backend
                'discount_amount' => $discountAmount,
                'coupon_id' => $couponId,
                'coupon_code' => $couponCode,
                
                // Datos del Cliente (Tomados del perfil del usuario)
                'contact_name' => $user->name,
                'contact_phone' => $user->phone ?? 'Sin teléfono registrado',
            ]);

            // Procesar Items
            foreach($cart as $id => $details) {
                // Bloqueamos el producto para evitar errores de stock simultáneo
                $product = Product::lockForUpdate()->find($id);

                if($product->stock < $details['quantity']) {
                    throw new \Exception("No hay suficiente stock de: " . $product->name);
                }

                // Descontar Stock
                $product->decrement('stock', $details['quantity']);

                // Guardar Item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'product_name' => $details['name'],
                    'quantity' => $details['quantity'],
                    'price' => $details['price']
                ]);
            }

            // Confirmar transacción
            DB::commit();

            // Vaciar Carrito
            session()->forget(['cart', 'coupon']);

            return redirect()->route('home')->with('success', '¡Pedido #' . $order->id . ' recibido! Gracias por tu compra.');

        } catch (\Exception $e) {
            DB::rollback(); // Deshacer cambios si falla
            return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }
    
    // --- NUEVAS FUNCIONES PARA "MIS PEDIDOS" ---

    public function index()
    {
        // Obtenemos los pedidos SOLO del usuario logueado, paginados
        $orders = Auth::user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        // Buscamos el pedido, pero aseguramos que sea del usuario actual (Seguridad)
        $order = Order::where('user_id', Auth::id())
                      ->with('items')
                      ->findOrFail($id);

        return view('orders.show', compact('order'));
    }
}