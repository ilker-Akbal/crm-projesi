<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',      
        'tax_number',
        'address',
        'phone_number',
        'email',
        'registration_date',
        'foundation_date', // BURAYA EKLENDİ
        'current_role',
        'customer_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'foundation_date'   => 'date', // TARİH OLARAK CAST EDİLDİ
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
