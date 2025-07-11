<?php
// app/Models/Company.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory; // , Blameable;

    /* Inverse */
    public function customer() { return $this->belongsTo(Customer::class); }

    /* One-to-Many */
    public function contacts() { return $this->hasMany(Contact::class); }
}
