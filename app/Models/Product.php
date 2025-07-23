<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

   protected $fillable = [
    'product_name',      // ✅
    'customer_id',

    'explanation',
    'created_by',
    'updated_by',
];

public function serials()
{
    return $this->hasMany(ProductSerial::class);
}
    public function customer()      { return $this->belongsTo(Customer::class); }
    public function stocks()        { return $this->hasMany(ProductStock::class); }
    public function prices()        { return $this->hasMany(ProductPrice::class); }
    public function orderLines()    { return $this->hasMany(OrderProduct::class, 'Order_id'); }
    public function offerLines()    { return $this->hasMany(OfferProduct::class); }


    public function getLatestPriceAttribute()
    {
        return $this->prices()
                    ->latest('updated_at')
                    ->value('price') ?? 0;
    }

    /* --- Mevcut stok (son stok kaydı) --- */
    public function getCurrentStockAttribute()
{
    // update_date eşit olduğunda sıra karışmasın diye id’ye bakıyoruz
    return $this->stocks()
                ->orderByDesc('id')        // ← sadece bu satır değişti
                ->value('stock_quantity') ?? 0;
}
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')
                    ->withPivot(['amount','unit_price'])
                    ->withTimestamps();
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_products')
                    ->withPivot(['amount','unit_price'])
                    ->withTimestamps();
    }
}
