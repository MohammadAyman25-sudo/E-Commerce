<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $fillable = [
        'quantity',
        'price_at_purchase',
        'product_id',
        'order_id',
    ];

    public function orders() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function products() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
