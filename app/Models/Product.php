<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name',
        'price',
        'description',
        'stock_quantity',
        'image_paths',
    ];

    protected function casts() {
        return [
            'image_paths' => 'array',
        ];
    }

    public function order_items() {
        return $this->hasMany(OrderItem::class);
    }
}
