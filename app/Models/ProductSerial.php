<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSerial extends Model
{
     public const AVAILABLE      = 'available';
    public const RESERVED = 'reserved';
    public const BLOCKED   = 'blocked'; 
    public const SOLD     = 'sold';
    protected $fillable = [
        'product_id',
        'order_id',         // ← eklendi
        'serial_number',
        'status',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'status' => 'string',
    ];
//public function serials() { return $this->hasMany(ProductSerial::class); }

/* app/Models/Order.php */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
}
