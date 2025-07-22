<?php
// app/Observers/OfferObserver.php

namespace App\Observers;

use App\Models\Offer;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfferObserver
{
    public function updated(Offer $offer): void
    {
        // daha önce order oluşmadıysa ve kabul edildiyse
        if ($offer->isDirty('status')
            && $offer->status === 'kabul'
            && is_null($offer->order_id)) {

            DB::transaction(function () use ($offer) {

                /* --- 1) Order oluştur --- */
                $order = Order::create([
                    'customer_id'   => $offer->customer_id,
                    'order_date'    => now(),
                    'delivery_date' => null,
                    'situation'     => 'hazırlanıyor',
                    'total_amount'  => $offer->total_amount,
                    'updated_by'    => Auth::id(),
                ]);

                /* --- 2) Ürün satırlarını taşı --- */
                foreach ($offer->products as $p) {
                    $order->products()->attach(
                        $p->id,
                        ['amount' => $p->pivot->amount,
                         'unit_price' => $p->pivot->unit_price]
                    );
                }

                /* --- 3) Teklifle ilişkilendir --- */
                $offer->order_id = $order->id;
                $offer->saveQuietly();
            });
        }
    }
}
