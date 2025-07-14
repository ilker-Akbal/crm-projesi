<?php
// app/Models/Company.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

     protected $fillable = [
        'company_name',      // ✅ küçük harf
        'tax_number',
        'address',
        'phone_number',
        'email',
        'registration_date',
        'current_role',
        'customer_id',
        'created_by',
        'updated_by',
    ];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function contacts() { return $this->hasMany(Contact::class); }
}
