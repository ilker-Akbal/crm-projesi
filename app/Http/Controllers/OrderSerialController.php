<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductSerial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderSerialController extends Controller
{
    /* Formu göster */
/* Formu göster */
public function create(Order $order)
{
    abort_if($order->customer_id !== auth()->id(), 403);

    // 1) Satış ise: henüz ödenmemiş olmalı
    if ($order->order_type === Order::SALE && $order->is_paid) {
        abort(403, 'Bu satış için seri rezervasyonu kapalı.');
    }

    // 2) Satın-alma ise: her zaman izin ver
    // ----------------------------------------------------

    // 3) Seri atanmamış ilk satırı bul
    $line = $order->products()->whereDoesntHave('serials')->first();

    if (! $line) {
        return redirect()->route('orders.show', $order)
                         ->with('success', 'Tüm seri numaraları zaten kaydedilmiş.');
    }

    return view('orders.serials.create', [
        'order'   => $order,
        'product' => $line,
        'qty'     => $line->pivot->amount,
    ]);
}

/* ---------- SERİLERİ KAYDET ---------- */
// app/Http/Controllers/OrderSerialController.php

public function store(Request $request, Order $order)
{
    $data = $request->validate([
        'product_id'  => 'required|exists:products,id',
        'serials'     => 'required|array|size:'.$request->input('qty'),
        'serials.*'   => 'required|string|distinct|unique:product_serials,serial_number',
    ]);

    $product = $order->products()->findOrFail($data['product_id']);
    $expect  = $product->pivot->amount;

    // 1) Seri kayıtları
    foreach ($data['serials'] as $sn) {
        ProductSerial::create([
            'order_id'      => $order->id,
            'product_id'    => $product->id,
            'serial_number' => $sn,
            'status'        => $order->order_type === Order::SALE
                                 ? ProductSerial::RESERVED
                                 : ProductSerial::AVAILABLE,
            'created_by'    => Auth::id(),
        ]);
    }

    // 2) Purchase siparişi için stok artışı burada
    if ($order->order_type === Order::PURCHASE) {
        // OrderController’daki moveStock() yardımcı metodunu çağırıyoruz
        resolve(\App\Http\Controllers\OrderController::class)
            ->moveStock($product->id, $expect);
    }

    // 3) Sonraki ürün var mı?
    $next = $order->products()->whereDoesntHave('serials')->first();

    // 4) Hepsi tamamlandıysa siparişi “tamamlandı” yap
    if (!$next && $order->order_type === Order::PURCHASE) {
        $order->update(['situation' => 'tamamlandı']);
    }

    return $next
        ? redirect()->route('orders.serials.create', $order)
                    ->with('success', 'Seri numaraları kaydedildi – sonraki ürüne geçin.')
        : redirect()->route('orders.show', $order)
                    ->with('success', 'Tüm seri numaraları girildi ve stoklara eklendi!');
}


}
