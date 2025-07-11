<?php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory; // , Blameable;

    public function customer() { return $this->belongsTo(Customer::class); }

    /* Bir ürün birçok stok & fiyat kaydına sahip olabilir */
    public function stocks()  { return $this->hasMany(ProductStock::class); }
    public function prices()  { return $this->hasMany(ProductPrice::class); }

    /* Pivot ilişkiler */
    public function orderLines() { return $this->hasMany(OrderProduct::class); }
    public function offerLines() { return $this->hasMany(OfferProduct::class); }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')
                    ->withPivot(['amount', 'unit_price'])
                    ->withTimestamps();
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_products')
                    ->withPivot(['amount', 'unit_price'])
                    ->withTimestamps();
    }
}
