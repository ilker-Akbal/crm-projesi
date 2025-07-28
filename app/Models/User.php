<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'role',
        'active',
        'customer_id',
        'created_by',
        'updated_by',
    ];

    protected $hidden = ['password', 'remember_token'];

    /* ---------- Audit ---------- */
    protected static function booted()
    {
        static::creating(function ($u) {
            $u->created_by = Auth::id();    // nullable; oturum yoksa null kurallarına uyar
            $u->updated_by = Auth::id();
        });

        static::updating(function ($u) {
            $u->updated_by = Auth::id();
        });
    }

    /* ---------- İlişkiler ---------- */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }
}
