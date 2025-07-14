<?php
// app/Models/OfferProduct.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferProduct extends Model
{
    use HasFactory;

    protected $table = 'offer_products';

    protected $fillable = [
        'offer_id',
        'product_id',
        'amount',
        'unit_price',
        'updated_by',
    ];

    public function offer()   { return $this->belongsTo(Offer::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
