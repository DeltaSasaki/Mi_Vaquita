<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Verificar si el cupón es válido en general
    public function isValid()
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        return true;
    }

    // Calcular el descuento basado en los items del carrito
    public function calculateDiscount($cartItems)
    {
        $discount = 0;
        $applicableTotal = 0;

        // 1. Calcular sobre qué monto aplica el descuento
        foreach ($cartItems as $item) {
            $itemPrice = $item['price'] * $item['quantity'];
            
            // Si el cupón es específico de una categoría
            if ($this->category_id) {
                // Asumimos que guardas category_id en el carrito, si no, habría que buscarlo
                // Para simplificar, si no está en sesión, este filtro requeriría cargar el producto
                $product = Product::find($item['id'] ?? $item['product_id']); 
                if ($product && $product->category_id == $this->category_id) {
                    $applicableTotal += $itemPrice;
                }
            } 
            // Si el cupón es específico de un producto
            elseif ($this->product_id) {
                if (($item['id'] ?? $item['product_id']) == $this->product_id) {
                    $applicableTotal += $itemPrice;
                }
            } 
            // Si es global
            else {
                $applicableTotal += $itemPrice;
            }
        }

        // Validar compra mínima
        if ($this->min_purchase && $applicableTotal < $this->min_purchase) {
            return 0;
        }

        // 2. Calcular el monto final
        if ($this->type === 'fixed') {
            $discount = $this->value;
        } elseif ($this->type === 'percent') {
            $discount = ($applicableTotal * $this->value) / 100;
        }

        // Asegurar que el descuento no sea mayor al total aplicable
        return min($discount, $applicableTotal);
    }
}
