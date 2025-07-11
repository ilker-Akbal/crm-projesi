<?php
// app/Models/ProductPrice.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory; // , Blameable;

    public function product() { return $this->belongsTo(Product::class); }
}
