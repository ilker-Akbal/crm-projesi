<?php
// app/Models/ProductStock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id','stock_quantity','blocked_stock',
  'reserved_stock','update_date','updated_by'
    ];

    // Doğru kullanım ➊ (konvansiyona uyuyorsanız)
    public function product()
    {
        return $this->belongsTo(Product::class);   // product_id sütununu otomatik kullanır
    }

    protected $appends = ['available_stock'];
protected $casts = ['update_date' => 'datetime'];

public function getAvailableStockAttribute(): int
{
    return max(
        0,
        $this->stock_quantity - $this->blocked_stock - $this->reserved_stock
    );
}
}
