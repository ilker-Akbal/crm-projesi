<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSerial extends Model
{
    protected $fillable = [
      'product_id','serial_number','status',
      'created_by','updated_by'
    ];
public function serials() { return $this->hasMany(ProductSerial::class); }

/* app/Models/Order.php */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
}
