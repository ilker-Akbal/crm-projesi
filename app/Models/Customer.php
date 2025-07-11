<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /* ----------------------------------------------------------------
     |  Mass-assignment ayarları (örnek)
     * ---------------------------------------------------------------- */
    protected $fillable = [
        'customer_name',
        'customer_type',
        'phone',
        'email',
        'address',
    ];

    /* ----------------------------------------------------------------
     |  İlişkiler
     * ---------------------------------------------------------------- */
    public function orders()          { return $this->hasMany(Order::class); }
    public function offers()          { return $this->hasMany(Offer::class); }
    public function companies()       { return $this->hasMany(Company::class); }
    public function actions()         { return $this->hasMany(Action::class); }
    public function reminders()       { return $this->hasMany(Reminder::class); }
    public function supportRequests() { return $this->hasMany(SupportRequest::class); }
    public function currentCards()    { return $this->hasMany(CurrentCard::class); }
}
