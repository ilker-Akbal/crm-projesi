<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // tablo adı "users" olduğu için property gerekmez
    protected $fillable = [
        'username', 'role', 'password', 'active',
        'created_by', 'updated_by',
    ];

    protected $hidden = ['password', 'remember_token'];

    /* ======= İlişkiler ======= */
    public function actions()    { return $this->hasMany(Action::class); }
    public function reminders()  { return $this->hasMany(Reminder::class); }
}
