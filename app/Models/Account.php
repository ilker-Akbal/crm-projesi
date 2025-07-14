<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    // Eğer veritabanı tablonuzun adı current_cards ise:
    protected $table = 'current_cards';

    protected $fillable = [
        'customer_id',
        'balance',
        'opening_date',
        'updated_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
