<?php
// app/Models/Customer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; 
class Customer extends Model
{
    use HasFactory;

   protected $fillable = [
        'customer_name',
        'customer_type',
        'phone',
        'email',
        'address',
        'created_by',
        'updated_by',
    ];

    protected static function booted()
    {
        // Yeni kayıt oluşturulurken
        static::creating(function ($customer) {
            $customer->created_by = Auth::id() ?? 1;
            $customer->updated_by = Auth::id() ?? 1;
        });

        // Varolan kayıt güncellenirken
        static::updating(function ($customer) {
            $customer->updated_by = Auth::id() ?? 1;
        });
    }
    public function orders()          { return $this->hasMany(Order::class); }
    public function offers()          { return $this->hasMany(Offer::class); }
    public function currentCards()    { return $this->hasMany(CurrentCard::class); }
    public function actions()         { return $this->hasMany(Action::class); }
    public function reminders()       { return $this->hasMany(Reminder::class); }
    public function supportRequests() { return $this->hasMany(SupportRequest::class); }
    public function companies()       { return $this->hasMany(Company::class); }
}
