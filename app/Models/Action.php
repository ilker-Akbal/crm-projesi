<?php
// app/Models/Action.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\User;

class Action extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'action_type',
        'action_date',
        'updated_by',
    ];

    protected $casts = [
        'action_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
