<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;

class OrderController extends Controller
{
    /* -------------------------------------------------
     |  GET /orders  →  Liste
     * ------------------------------------------------*/
    public function index()
    {
        $orders = Order::where('customer_id', Auth::user()->customer_id)
                       ->with('customer')
                       ->orderBy('order_date', 'desc')
                       ->get();

        return view('orders.index', compact('orders'));
    }

    /* -------------------------------------------------
     |  GET /orders/create  →  Form
     * ------------------------------------------------*/
    public function create()
    {
        // Yalnızca kendi müşterisi (isterseniz dropdown’ı tamamen kaldırabilirsiniz)
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();

        /* ürünler – en güncel fiyatıyla */
        $products = Product::where('customer_id', Auth::user()->customer_id)
                           ->with(['prices' => fn($q) => $q->latest()->limit(1)])
                           ->orderBy('product_name')
                           ->get()
                           ->map(function ($p) {
                               return [
                                   'id'           => $p->id,
                                   'product_name' => $p->product_name,
                                   'unit_price'   => optional($p->prices->first())->price ?? 0,
                               ];
                           });

        return view('orders.create', compact('customers', 'products'));
    }

    /* -------------------------------------------------
     |  POST /orders  →  Kaydet
     * ------------------------------------------------*/
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_date'             => 'required|date',
            'delivery_date'          => 'nullable|date|after_or_equal:order_date',
            'items'                  => 'required|array|min:1',
            'items.*.product_id'     => 'required|exists:products,id',
            'items.*.amount'         => 'required|numeric|min:1',
            'items.*.unit_price'     => 'required|numeric|min:0',
            'situation'              => 'in:hazırlanıyor,tamamlandı',
        ]);

        /* toplam hesapla */
        $total = collect($data['items'])
                    ->reduce(fn($sum, $i) => $sum + $i['amount'] * $i['unit_price'], 0);

        $order = Order::create([
            'customer_id'   => Auth::user()->customer_id,
            'order_date'    => $data['order_date'],
            'delivery_date' => $data['delivery_date'] ?? null,
            'situation'     => 'hazırlanıyor',
            'total_amount'  => $total,
        ]);

        /* pivot ekle */
        foreach ($data['items'] as $item) {
            $order->products()->attach(
                $item['product_id'],
                ['amount' => $item['amount'], 'unit_price' => $item['unit_price']]
            );
        }

        return redirect()->route('orders.index')
                         ->with('success', 'Order created successfully.');
    }

    /* -------------------------------------------------
     |  GET /orders/{order}  →  Detay
     * ------------------------------------------------*/
    public function show(Order $order)
    {
        $order->load(['customer', 'products']);

        return view('orders.show', compact('order'));
    }

    /* -------------------------------------------------
     |  GET /orders/{order}/edit  →  Düzenle Formu
     * ------------------------------------------------*/
    public function edit(Order $order)
    {
        $order->load('products');

        $customers = Customer::whereKey(Auth::user()->customer_id)->get();

        $products  = Product::where('customer_id', Auth::user()->customer_id)
                            ->orderBy('product_name')
                            ->get();

        return view('orders.edit', compact('order', 'customers', 'products'));
    }

    /* -------------------------------------------------
     |  PUT /orders/{order}  →  Güncelle
     * ------------------------------------------------*/
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'order_date'             => 'required|date',
            'delivery_date'          => 'nullable|date|after_or_equal:order_date',
            'items'                  => 'required|array|min:1',
            'items.*.product_id'     => 'required|exists:products,id',
            'items.*.amount'         => 'required|numeric|min:1',
            'items.*.unit_price'     => 'required|numeric|min:0',
        ]);

        $total = collect($data['items'])
                    ->reduce(fn($sum, $i) => $sum + $i['amount'] * $i['unit_price'], 0);

        $order->update([
            'customer_id'   => Auth::user()->customer_id,
            'order_date'    => $data['order_date'],
            'delivery_date' => $data['delivery_date'] ?? null,
            'total_amount'  => $total,
        ]);

        /* pivot yeniden oluştur */
        $order->products()->sync([]); // detach yerine sync([])
        foreach ($data['items'] as $item) {
            $order->products()->attach(
                $item['product_id'],
                ['amount' => $item['amount'], 'unit_price' => $item['unit_price']]
            );
        }

        return redirect()->route('orders.index')
                         ->with('success', 'Order updated successfully.');
    }

    /* -------------------------------------------------
     |  DELETE /orders/{order}  →  Sil
     * ------------------------------------------------*/
    public function destroy(Order $order)
    {
        $order->products()->detach();
        $order->delete();

        return redirect()->route('orders.index')
                         ->with('success', 'Order deleted successfully.');
    }
}
