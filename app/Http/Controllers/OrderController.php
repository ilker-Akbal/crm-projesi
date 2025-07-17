<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // GET /orders
    public function index()
    {
        $orders = Order::with('customer')
                       ->orderBy('order_date', 'desc')     // alan adı düzeltildi
                       ->get();

        return view('orders.index', compact('orders'));
    }

    // GET /orders/create
   // GET /orders/create
public function create()
{
    $customers = Customer::orderBy('customer_name')->get();

    /* en güncel fiyatı da çek (yoksa 0) */
    $products = Product::with(['prices' => function ($q) {
                        $q->latest()->limit(1);
                    }])
                    ->orderBy('product_name')
                    ->get()
                    ->map(function ($p) {
                        return [
                            'id'          => $p->id,
                            'product_name'=> $p->product_name,
                            'unit_price'  => optional($p->prices->first())->price ?? 0
                        ];
                    });

    return view('orders.create', compact('customers', 'products'));
}


    // POST /orders
   public function store(Request $request)
{
    $data = $request->validate([
        'customer_id'           => 'required|exists:customers,id',
        'order_date'            => 'required|date',
        'delivery_date'         => 'nullable|date|after_or_equal:order_date',
        'items'                 => 'required|array|min:1',
        'items.*.product_id'    => 'required|exists:products,id',
        'items.*.amount'        => 'required|numeric|min:1',
        'items.*.unit_price'    => 'required|numeric|min:0',
        'situation' => 'in:hazırlanıyor,tamamlandı'
    ]);

    // toplamı hesapla
    $total = collect($data['items'])
               ->reduce(fn($sum, $i) => $sum + $i['amount'] * $i['unit_price'], 0);

    $order = Order::create([
        'customer_id'   => $data['customer_id'],
        'order_date'    => $data['order_date'],
        'delivery_date' => $data['delivery_date'] ?? null,
        'situation'     => 'hazırlanıyor',      // ← burayı ekledik
        'total_amount'  => $total,
    ]);

    // pivot ekle
    foreach ($data['items'] as $item) {
        $order->products()->attach(
            $item['product_id'],
            ['amount' => $item['amount'], 'unit_price' => $item['unit_price']]
        );
    }

    return redirect()->route('orders.index')
                     ->with('success','Order created successfully.');
}

    // GET /orders/{order}
    public function show(Order $order)
    {
        $order->load(['customer', 'products']);
        return view('orders.show', compact('order'));
    }

    // GET /orders/{order}/edit
    public function edit(Order $order)
    {
        $order->load('products');
        $customers = Customer::orderBy('customer_name')->get();
        $products  = Product::orderBy('product_name')->get();

        return view('orders.edit', compact('order', 'customers', 'products'));
    }

    // PUT /orders/{order}
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'customer_id'            => 'required|exists:customers,id',
            'order_date'             => 'required|date',
            'delivery_date'          => 'nullable|date|after_or_equal:order_date',
            'items'                  => 'required|array|min:1',
            'items.*.product_id'     => 'required|exists:products,id',
            'items.*.amount'         => 'required|numeric|min:1',
            'items.*.unit_price'     => 'required|numeric|min:0',
        ]);

        $total = 0;
        foreach ($data['items'] as $item) {
            $total += $item['amount'] * $item['unit_price'];
        }

        $order->update([
            'customer_id'   => $data['customer_id'],
            'order_date'    => $data['order_date'],
            'delivery_date' => $data['delivery_date'] ?? null,
            'total_amount'  => $total,
        ]);

        // pivot yeniden oluştur
        $order->products()->detach();
        foreach ($data['items'] as $item) {
            $order->products()->attach(
                $item['product_id'],
                ['amount' => $item['amount'], 'unit_price' => $item['unit_price']]
            );
        }

        return redirect()->route('orders.index')
                         ->with('success', 'Order updated successfully.');
    }

    // DELETE /orders/{order}
    public function destroy(Order $order)
    {
        $order->products()->detach();
        $order->delete();

        return redirect()->route('orders.index')
                         ->with('success', 'Order deleted successfully.');
    }
}
