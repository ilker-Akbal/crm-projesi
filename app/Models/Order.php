<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'situation',
        'customer_id',
        'order_date',      // düzeltilmiş alan adları
        'delivery_date',
        'total_amount',
        'updated_by',
    ];
    protected $casts = [
        'order_date'    => 'date',
        'delivery_date' => 'date',
    ];

    public function customer()      { return $this->belongsTo(Customer::class); }
    public function orderProducts() { return $this->hasMany(OrderProduct::class); }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')
                    ->withPivot(['amount', 'unit_price'])
                    ->withTimestamps();
    }
}
