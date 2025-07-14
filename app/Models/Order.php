<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'contacts',
        'situation',
        'customer_id',
        'order_Date',
        'delivery_date',
        'total_amount',
        'updated_by',
    ];

    public function customer()      { return $this->belongsTo(Customer::class); }
    public function orderProducts() { return $this->hasMany(OrderProduct::class); }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')
                    ->withPivot(['amount','unit_price'])
                    ->withTimestamps();
    }
}
