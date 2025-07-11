<?php
// app/Models/CurrentCard.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentCard extends Model
{
    use HasFactory; // , Blameable;

    public function customer()        { return $this->belongsTo(Customer::class); }
    public function movements()       { return $this->hasMany(CurrentMovement::class); }
}
