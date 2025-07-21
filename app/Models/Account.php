<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Account extends Model
{
    use HasFactory;

    /** current_cards tablosunu kullanıyoruz */
    protected $table = 'current_cards';

    protected $fillable = [
        'customer_id',
        'balance',
        'opening_date',
        'updated_by',
    ];

    /* ----------- İlişkiler ----------- */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**  <— EKLENDİ —> **/
    public function movements()
    {
        return $this->hasMany(CurrentMovement::class, 'current_id')
                    ->orderBy('departure_date', 'desc');
    }

    /* -------- Dinamik bakiye -------- */
    public function getComputedBalanceAttribute(): float
{
    $sum = $this->movements()
                ->reorder()               // <-- ORDER BY’ı sıfırlar
                ->selectRaw("
                    COALESCE(SUM(
                      CASE
                        WHEN movement_type = ? THEN amount    -- Credit +
                        WHEN movement_type = ? THEN -amount   -- Debit  -
                      END
                    ),0) AS bal",
                    [CurrentMovement::CREDIT, CurrentMovement::DEBIT]
                )
                ->value('bal');

    return round($sum, 2);
}


    /* ------ Sadece kendi müşterisi ------ */
    protected static function booted()
    {
        if (auth()->check()) {
            static::addGlobalScope('owner', function (Builder $b) {
                $b->where('customer_id', auth()->user()->customer_id);
            });
        }
    }
}
