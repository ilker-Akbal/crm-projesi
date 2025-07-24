<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductSerial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderSerialController extends Controller
{
    /* Formu göster */
    public function create(Order $order)
{
    // yetki kontrolleri
    abort_if($order->customer_id !== auth()->user()->customer_id, 403);
    abort_unless(
        $order->order_type === Order::PURCHASE && $order->is_paid,
        403, 'Bu sipariş için seri no girilemez.'
    );

    // Henüz seri girilmemiş ürün var mı?
    $line = $order->products()
                  ->whereDoesntHave('serials')
                  ->first();

    if (!$line) {
        // Hepsi girilmiş → sipariş detayına veya listeye dön
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
            'serials.*'   => 'required|string|distinct',
        ]);

        $product = $order->products()->findOrFail($data['product_id']);
        $expect  = $product->pivot->amount;

        if (count($data['serials']) !== $expect) {
            return back()->withErrors([
                'serials' => "Bu ürün için $expect adet seri numarası girmelisiniz."
            ])->withInput();
        }

        foreach ($data['serials'] as $sn) {
            ProductSerial::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'serial_no'  => $sn,
            ]);
        }

        // sırada başka ürün kaldı mı?
        $nextLine = $order->products()->whereDoesntHave('serials')->first();

        if ($nextLine) {
            return redirect()
                ->route('orders.serials.create', $order)
                ->with('success', 'Seri numaraları kaydedildi – sıradaki ürüne geçin.');
        }

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Tüm seri numaraları kaydedildi!');
    }
}
