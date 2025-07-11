<?php
// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory; // , Blameable;

    /* Inverse */
    public function customer() { return $this->belongsTo(Customer::class); }
    public function contact()  { return $this->belongsTo(Contact::class); }

    /* Many-to-Many (pivot: order_products) */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')
                    ->withPivot(['amount', 'unit_price'])
                    ->withTimestamps();
    }

    /* Tekil erişmek isterseniz  */
    public function lines() { return $this->hasMany(OrderProduct::class); }
}
