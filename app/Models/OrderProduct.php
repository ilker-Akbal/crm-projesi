<?php
// app/Models/OrderProduct.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'order_products';

    protected $fillable = [
        'order_id',
        'product_id',
        'amount',
        'unit_price',
        'updated_by',
    ];

    public function order()   { return $this->belongsTo(Order::class, 'Order_id'); }
    public function product() { return $this->belongsTo(Product::class, 'Product_id'); }
}
