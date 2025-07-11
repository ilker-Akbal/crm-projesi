<?php
// app/Models/SupportRequest.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    use HasFactory; // , Blameable;

    public function customer() { return $this->belongsTo(Customer::class); }
}
