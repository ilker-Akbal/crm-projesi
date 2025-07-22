<?php

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

    /* ---------- Audit ---------- */
    protected static function booted()
    {
        // Oluşturma
        static::creating(function ($c) {
            $c->created_by = Auth::id() ?? 1;
            $c->updated_by = Auth::id() ?? 1;
        });

        // Güncelleme
        static::updating(function ($c) {
            $c->updated_by = Auth::id() ?? 1;
        });
    }

    /* ---------- İlişkiler ---------- */
    public function orders()          { return $this->hasMany(Order::class); }
    public function offers()          { return $this->hasMany(Offer::class); }
    public function currentCards()    { return $this->hasMany(CurrentCard::class); } // eski toplu erişim
    public function account()         { return $this->hasOne(CurrentCard::class); }  // TEK HESAP
    public function actions()         { return $this->hasMany(Action::class); }
    public function reminders()       { return $this->hasMany(Reminder::class); }
    public function supportRequests() { return $this->hasMany(SupportRequest::class); }
    public function companies()       { return $this->hasMany(Company::class); }
    public function user()            { return $this->hasOne(User::class); }
}
