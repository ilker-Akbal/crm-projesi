<?php
// app/Observers/OrderObserver.php

namespace App\Observers;

use App\Models\Order;
use App\Models\CurrentMovement;

class OrderObserver
{
    /** created + updated */
    public function saved(Order $order): void
    {
        $this->createPaymentMovementIfNeeded($order);
    }

    /* ---------- Yardımcı ---------- */
    private function createPaymentMovementIfNeeded(Order $order): void
    {
        // 1) Zaten hareket oluşturulmuş mu?
        if ($order->payment_movement_id) {
            return;
        }

        // 2) “is_paid” alanı:
        //    • yeni kayıtta TRUE  –̶o̶r̶–̶
        //    • güncellemede false→true değiştiyse
        $becamePaid = $order->wasRecentlyCreated
                     ?  $order->is_paid
                     : ($order->wasChanged('is_paid') && $order->is_paid);

        if (! $becamePaid) {
            return;                                // koşullar yok
        }

        // 3) Bağlı cari kart var mı?
        $card = $order->customer->account;
        if (! $card) {
            return;
        }

        // 4) Hareketi oluştur
        $movement = CurrentMovement::create([
            'current_id'     => $card->id,
            'company_id'     => $order->company_id,
            'departure_date' => now(),
            'movement_type'  => $order->order_type === Order::SALE
                                ? CurrentMovement::CREDIT   // satış → alacak
                                : CurrentMovement::DEBIT,   // alış  → borç
            'amount'         => $order->total_amount,
            'explanation'    => 'Order #'.$order->id.' – Ödeme',
            'updated_by'     => auth()->id(),
        ]);

        // 5) Siparişe ilişkilendir (ikinci kez eklenmesin)
        $order->payment_movement_id = $movement->id;
        $order->saveQuietly(); // yeniden saved tetiklenir fakat 1. adımda döner
    }
}
