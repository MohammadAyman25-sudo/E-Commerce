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
    ];

    public function images() {
        return $this->hasMany(ProductImages::class);
    }
}
