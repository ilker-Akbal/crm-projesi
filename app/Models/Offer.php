<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'company_id',
        'order_id',
        'offer_date',
        'delivery_date', // ← yeni alan eklendi
        'valid_until',
        'status',
        'updated_by',
        'total_amount',
    ];

    protected $casts = [
        'offer_date'    => 'date',
        'delivery_date' => 'date', // ← yeni alan eklendi
        'valid_until'   => 'date',
    ];

    // İlişkiler
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function lines()
    {
        return $this->hasMany(OfferProduct::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_products')
                    ->withPivot(['amount', 'unit_price'])
                    ->wherePivot('amount', '>', 0) // 0’dan büyük olanlar
                    ->withTimestamps();
    }
}
