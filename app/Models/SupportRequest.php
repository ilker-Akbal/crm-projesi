<?php
// app/Models/SupportRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    use HasFactory;

    protected $table = 'support_requests';

    protected $fillable = [
        'customer_id',
        'title',
        'explanation',
        'situation',
        'registration_date',
        'updated_by',
    ];

    public function customer() { return $this->belongsTo(Customer::class); }
}
