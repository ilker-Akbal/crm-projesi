<?php
// app/Models/Offer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory; // , Blameable;

    public function customer() { return $this->belongsTo(Customer::class); }
    public function order()    { return $this->belongsTo(Order::class); }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_products')
                    ->withPivot(['amount', 'unit_price'])
                    ->withTimestamps();
    }

    public function lines() { return $this->hasMany(OfferProduct::class); }
}
