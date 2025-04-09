<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    protected $table = 'product_images';
    protected $fillable = [
        'image_path',
    ];

    public function products() {
        return $this->belongsTo(Product::class);
    }
}
