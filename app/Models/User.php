<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    protected $hidden = ['password','remember_token'];

    public function actions()   { return $this->hasMany(Action::class); }
     public function customer() { return $this->belongsTo(Customer::class); }
    public function reminders() { return $this->hasMany(Reminder::class); }
}
