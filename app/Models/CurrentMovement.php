<?php
// app/Models/CurrentMovement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentMovement extends Model
{
    use HasFactory;

    protected $table = 'current_movements';

    protected $fillable = [
        'current_id',
        'departure_date',
        'amount',
        'movement_type',
        'explanation',
        'updated_by',
    ];

    public function currentCard() { return $this->belongsTo(CurrentCard::class, 'current_id'); }
}
