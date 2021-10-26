<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
    protected $table = 'product_variant_prices';
    protected $guarded = [];


    public function productVariantOne()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_one');
    }

    public function productVariantTwo()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_two');
    }

    public function productVariantThree()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_three');
    }
}
