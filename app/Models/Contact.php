<?php
// app/Models/Contact.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'position',
        'email',
        'phone',
        'updated_by',
    ];

    public function company() { return $this->belongsTo(Company::class); }
}
