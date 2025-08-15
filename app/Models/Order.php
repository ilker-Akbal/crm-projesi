<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /*------------- Sabitler -------------*/
    public const SALE     = 'sale';
    public const PURCHASE = 'purchase';

    /*------------- Mass-assignment -------------*/
    protected $fillable = [
        'customer_id',
        'company_id',
        'order_type',
        'situation',
        'order_date',
        'delivery_date',
        'total_amount',
        'is_paid',
        'paid_at',
        'payment_movement_id',
        'updated_by',
    ];

    /*------------- Dönüşümler -------------*/
    protected $casts = [
        'order_date'    => 'date',
        'delivery_date' => 'date',
        'is_paid'       => 'boolean',
        'paid_at'       => 'datetime',
    ];

    /*------------- İlişkiler -------------*/
    public function customer()      { return $this->belongsTo(Customer::class); }
    public function orderProducts() { return $this->hasMany(OrderProduct::class); }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')
                    ->withPivot(['amount', 'unit_price'])
                    ->withTimestamps();
    }

    public function serials()         { return $this->hasMany(ProductSerial::class); }
    public function paymentMovement() { return $this->belongsTo(CurrentMovement::class, 'payment_movement_id'); }

    public function company()
    {
        return $this->belongsTo(Company::class)
                    ->withDefault(['company_name' => '―']);
    }

    public function getDisplayCompanyNameAttribute()
    {
    return $this->company?->company_name 
        ?? $this->offer?->company?->company_name 
        ?? '—';
    }


    /** Offer -> Order (offers.order_id foreign key) ters ilişkisi */
    public function offer()
    {
        return $this->hasOne(Offer::class, 'order_id'); // ← eklendi
    }
}
