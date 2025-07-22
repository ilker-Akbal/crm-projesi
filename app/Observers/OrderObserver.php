<?php
// app/Observers/OrderObserver.php

namespace App\Observers;

use App\Models\Order;
use App\Models\ProductStock;
use App\Models\CurrentMovement;
use App\Models\CurrentCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// app/Observers/OrderObserver.php
class OrderObserver
{
    public function updated(Order $order): void
    {
        /* 1) Sipariş tamamlandıysa hâlen bakiyeye ekleniyor (önceki bölüm) */

        /* 2) Ödeme yeni işaretlendiyse — daha önce hareket yoksa */
        if (
            $order->isDirty('is_paid') &&                 // field değişti
            $order->is_paid &&                            // true oldu
            is_null($order->payment_movement_id)          // daha önce eklenmemiş
        ) {
            $card = $order->customer->account;            // tek cari kart
            if ($card) {
                $movement = \App\Models\CurrentMovement::create([
                    'current_id'     => $card->id,
                    'departure_date' => now(),
                    'movement_type'  => $order->order_type === 'sale'
                                        ? \App\Models\CurrentMovement::CREDIT
                                        : \App\Models\CurrentMovement::DEBIT,
                    'amount'         => $order->total_amount,
                    'explanation'    => 'Order #'.$order->id.' – Ödeme',
                    'updated_by'     => auth()->id(),
                ]);

                // siparişte sakla → ikinci kez eklenmesin
                $order->payment_movement_id = $movement->id;
                $order->saveQuietly();
            }
        }
    }
}
