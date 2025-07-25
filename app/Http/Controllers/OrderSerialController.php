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
    // 1) Yetki
    abort_if($order->customer_id !== auth()->user()->customer_id, 403);

    // 2) Yalnızca SATIŞ ve henüz ödenmemiş siparişler için izin ver
    abort_unless(
        $order->order_type === Order::SALE && ! $order->is_paid,
        403, 'Bu sipariş için seri numarası rezervasyonu kapalı.'
    );

    // 3) Seri atanmamış ilk satırı bul
    $line = $order->products()
                  ->whereDoesntHave('serials')
                  ->first();

    if (! $line) {
        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Tüm seri numaraları zaten kaydedilmiş.');
    }

    return view('orders.serials.create', [
        'order'   => $order,
        'product' => $line,
        'qty'     => $line->pivot->amount,
    ]);
}



    /* Seri numaralarını kaydet */
    public function store(Request $request, Order $order)
{
    $data = $request->validate([
        'product_id'  => 'required|exists:products,id',
        'serials'     => 'required|array|min:1',
        'serials.*'   => 'required|string|distinct|unique:product_serials,serial_number',
    ]);

    // siparişteki ilgili ürün satırı
    $product = $order->products()->findOrFail($data['product_id']);
    $expect  = $product->pivot->amount;

    if (count($data['serials']) !== $expect) {
        return back()->withErrors([
            'serials' => "Bu ürün için tam $expect adet seri numarası girmelisiniz."
        ])->withInput();
    }

    foreach ($data['serials'] as $sn) {
        ProductSerial::create([
            'order_id'      => $order->id,
            'product_id'    => $product->id,
            'serial_number' => $sn,
            'status'        => ProductSerial::RESERVED, // ← rezerve olarak işaretle
            'created_by'    => Auth::id(),
        ]);
    }

    // sırada başka seri atanmamış ürün var mı?
    $nextLine = $order->products()->whereDoesntHave('serials')->first();

    return $nextLine
        ? redirect()->route('orders.serials.create', $order)
                    ->with('success', 'Seri numaraları kaydedildi – sonraki ürüne geçin.')
        : redirect()->route('orders.show', $order)
                    ->with('success', 'Tüm seri numaraları kaydedildi!');
}

}
