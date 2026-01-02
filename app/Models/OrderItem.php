<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // ESTA ES LA SOLUCIÓN AL ERROR:
    // Autorizamos qué campos se pueden guardar
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price'
    ];

    // Relación: Un Item pertenece a una Orden
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relación: Un Item pertenece a un Producto original
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}