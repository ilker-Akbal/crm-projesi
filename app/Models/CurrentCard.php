<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CurrentCard extends Model
{
    use HasFactory;

    protected $table = 'current_cards';

    protected $fillable = [
        'customer_id',
        'balance',
        'opening_date',
        'updated_by',
    ];

    /* ---------- İlişkiler ---------- */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function movements()
    {
        return $this->hasMany(CurrentMovement::class, 'current_id')
                    ->orderBy('departure_date', 'desc');
    }

    /* ---------- Dinamik bakiye ---------- */
    public function getComputedBalanceAttribute(): float
    {
        $sum = $this->movements()
                    ->selectRaw("
                        SUM(
                          CASE
                            WHEN movement_type = ? THEN amount
                            WHEN movement_type = ? THEN -amount
                          END
                        ) AS bal",
                        [CurrentMovement::CREDIT, CurrentMovement::DEBIT]
                    )
                    ->value('bal');

        return round($sum ?? 0, 2);
    }

    /* ---------- Global Scope: kendi müşterisi ---------- */
    protected static function booted()
    {
        if (auth()->check()) {
            static::addGlobalScope('owner', function (Builder $b) {
                $b->where('customer_id', auth()->user()->customer_id);
            });
        }
    }
}
