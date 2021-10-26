<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    // public function productVariant()
    // {
    //     return $this->hasMany(ProductVariant::class);
    // }

    public function productVariantPrice()
    {
        return $this->hasMany(ProductVariantPrice::class);
    }

}
