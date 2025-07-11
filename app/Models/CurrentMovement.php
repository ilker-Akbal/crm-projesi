<?php
// app/Models/CurrentMovement.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentMovement extends Model
{
    use HasFactory; // , Blameable;

    public function currentCard() { return $this->belongsTo(CurrentCard::class, 'current_id'); }
}
