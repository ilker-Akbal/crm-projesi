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
        'balance',        // Opsiyonel: hâlen tutuyorsanız
        'opening_date',
        'updated_by',
    ];

    /* ------------ İlişkiler ------------ */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function movements()
    {
        // En yeni ilk sırada
        return $this->hasMany(CurrentMovement::class, 'current_id')
                    ->orderBy('departure_date', 'desc');
    }

    /* ------------ Dinamik bakiye (Dr/Cr farkı) ------------ */
    /* ------------ Dinamik bakiye (SQL üzerinden) ------------ */
public function getComputedBalanceAttribute(): float
{
    // Tek sorguda toplar; lazy-load’a bağlı kalmaz
    $sum = $this->movements()
                ->selectRaw("
                    SUM(
                      CASE
                        WHEN movement_type = ? THEN amount   -- Credit  → +
                        WHEN movement_type = ? THEN -amount  -- Debit   → -
                      END
                    ) AS bal",
                    [CurrentMovement::CREDIT, CurrentMovement::DEBIT]
                )
                ->value('bal');

    return round($sum ?? 0, 2);
}


    /* ------------ Global Scope: sadece kendi müşterisi ------------ */
    protected static function booted()
    {
        if (auth()->check()) {
            static::addGlobalScope('owner', function (Builder $builder) {
                $builder->where('customer_id', auth()->user()->customer_id);
            });
        }
    }
}
