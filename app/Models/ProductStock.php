<?php
// app/Models/ProductStock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'stock_quantity',
        'update_date',
        'updated_by',
    ];

    // Doğru kullanım ➊ (konvansiyona uyuyorsanız)
    public function product()
    {
        return $this->belongsTo(Product::class);   // product_id sütununu otomatik kullanır
    }

    /*  // veya ➋ (sütun adını elle veriyorsanız)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    */
}
