<?php
// app/Models/Reminder.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'reminder_date',
        'customer_id',
        'user_id',
        'explanation',
        'updated_by',
    ];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function user()     { return $this->belongsTo(User::class, 'user_id'); }
}
