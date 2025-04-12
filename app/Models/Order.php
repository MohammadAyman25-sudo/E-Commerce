<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'total_amount', 
        'status',
        'shipping_address',
    ];

    public function users() {
        return $this->belongsTo(User::class);
    }

    public function order_items() {
        return $this->hasMany(OrderItem::class);
    }
}
