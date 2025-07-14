<?php
// app/Models/ProductPrice.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/ProductPrice.php
class ProductPrice extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'price', 'updated_by'];

    // Doğru ilişki  ⬇️
    public function product()
    {
        return $this->belongsTo(Product::class);   // varsayılan foreign key: product_id
        // veya ->belongsTo(Product::class, 'product_id');
    }
}

