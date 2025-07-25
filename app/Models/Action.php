<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'contact_id',
        'user_id',          //  ≡ eklendi
        'action_type',
        'action_date',
        'status',
        'description',
        'updated_by',
    ];

    protected $casts = [
        'action_date' => 'date',
    ];

    /* İlişkiler ------------------------------------------------------------- */
    public function contact()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
