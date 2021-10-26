<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';
    protected $guarded = [];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
