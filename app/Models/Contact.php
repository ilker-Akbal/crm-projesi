<?php
// app/Models/Contact.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory; // , Blameable;

    public function company() { return $this->belongsTo(Company::class); }
    public function orders()  { return $this->hasMany(Order::class); }   // (order.contacts ilişkisi)
}
