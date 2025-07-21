<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CurrentMovement extends Model
{
    use HasFactory;

    public const DEBIT  = 'Debit';
    public const CREDIT = 'Credit';

    protected $fillable = [
        'current_id',
        'departure_date',
        'amount',
        'movement_type',
        'explanation',
        'updated_by',
    ];

    /* --------- İlişki --------- */
    public function currentCard()
    {
        return $this->belongsTo(CurrentCard::class, 'current_id');
    }

    /* --------- Otomatik müşteri filtresi --------- */
    protected static function booted()
{
    /* -------------------------------------------------
     | 1) Müşteri scope (değişmedi)
     * ------------------------------------------------*/
    if (auth()->check()) {
        static::addGlobalScope('owner', function (Builder $q) {
            $q->whereHas('currentCard',
                fn ($c) => $c->where('customer_id', auth()->user()->customer_id));
        });
    }

    /* -------------------------------------------------
     | 2) Bakiye senkronizasyonu
     * ------------------------------------------------*/
    static::created(function (self $m) {
        // Doğru delta = +amount (Credit)  |  -amount (Debit)
        $delta = $m->movement_type === self::DEBIT ? -$m->amount : $m->amount;
        $m->adjustCardBalance($delta);
    });

    static::updating(function (self $m) {
        $old = $m->getOriginal();
        $oldDelta = $old['movement_type'] === self::DEBIT ? -$old['amount'] : $old['amount'];
        $newDelta = $m->movement_type     === self::DEBIT ? -$m->amount     : $m->amount;
        $m->adjustCardBalance($newDelta - $oldDelta);
    });

    static::deleted(function (self $m) {
        $delta = $m->movement_type === self::DEBIT ? +$m->amount : -$m->amount;
        $m->adjustCardBalance($delta);
    });
}


    /* --------- Yardımcı --------- */
    private function adjustCardBalance(float $delta): void
    {
        $card = $this->currentCard;
        $card->balance += $delta;
        // Sessiz kaydet – history tetiklenmesin
        $card->saveQuietly();
    }
}
