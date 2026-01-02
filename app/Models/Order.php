<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Campos que permitimos guardar masivamente
    protected $fillable = [
        'user_id', 
        'status', 
        'payment_method',     // Método (pago móvil, efectivo...)
        'payment_reference',  // Referencia bancaria
        'total',
        'contact_name', 
        'contact_phone', 
        'address',
        'shipping_type',      // 'pickup' o 'delivery'
        'latitude',           // Coordenada para el mapa
        'longitude',           // Coordenada para el mapa
        'coupon_id',        // <--- Faltaba
        'coupon_code',      // <--- Faltaba
        'discount_amount'   // <--- Faltaba
    ];

    // Relación 1: Una Orden pertenece a un Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación 2: Una Orden tiene muchos Items (productos comprados)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}