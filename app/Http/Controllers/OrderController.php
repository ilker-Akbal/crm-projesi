<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;

class OrderController extends Controller
{
    /* ------------------------------------  GET /orders  */
    public function index()
    {
        $orders = Order::where('customer_id', Auth::user()->customer_id)
                       ->with('customer')
                       ->latest('order_date')
                       ->get();

        return view('orders.index', compact('orders'));
    }

    /* ------------------------------------  GET /orders/create  */
    public function create()
    {
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();

        $products = Product::where('customer_id', Auth::user()->customer_id)
                           ->with(['prices' => fn($q) => $q->latest()->limit(1)])
                           ->orderBy('product_name')
                           ->get()
                           ->map(fn($p) => [
                               'id'           => $p->id,
                               'product_name' => $p->product_name,
                               'unit_price'   => optional($p->prices->first())->price ?? 0,
                           ]);

        return view('orders.create', compact('customers', 'products'));
    }

    /* ------------------------------------  POST /orders  */
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_type'             => 'required|in:sale,purchase',
            'order_date'             => 'required|date',
            'delivery_date'          => 'nullable|date|after_or_equal:order_date',
            'items'                  => 'required|array|min:1',
            'items.*.product_id'     => 'required|exists:products,id',
            'items.*.amount'         => 'required|numeric|min:1',
            'items.*.unit_price'     => 'required|numeric|min:0',
            'situation'              => 'in:hazırlanıyor,tamamlandı',
            'is_paid'                => 'sometimes|boolean',
        ]);

        $total = collect($data['items'])
                    ->reduce(fn($s, $i) => $s + $i['amount'] * $i['unit_price'], 0);

        $isPaid = $request->boolean('is_paid');

        $order = Order::create([
            'customer_id'   => Auth::user()->customer_id,
            'order_type'    => $data['order_type'],
            'order_date'    => $data['order_date'],
            'delivery_date' => $data['delivery_date'] ?? null,
            'situation'     => $data['situation'] ?? 'hazırlanıyor',
            'total_amount'  => $total,
            'is_paid'       => $isPaid,
            'paid_at'       => $isPaid ? now() : null,
        ]);

        /* pivot */
        foreach ($data['items'] as $item) {
            $order->products()->attach(
                $item['product_id'],
                ['amount'=>$item['amount'],'unit_price'=>$item['unit_price']]
            );
        }

        return redirect()->route('orders.index')
                         ->with('success', 'Order created successfully.');
    }

    /* ------------------------------------  GET /orders/{order}  */
    public function show(Order $order)
    {
        $order->load(['customer','products']);
        return view('orders.show', compact('order'));
    }

    /* ------------------------------------  GET /orders/{order}/edit  */
    public function edit(Order $order)
    {
        $order->load('products');

        $customers = Customer::whereKey(Auth::user()->customer_id)->get();
        $products  = Product::where('customer_id', Auth::user()->customer_id)
                            ->orderBy('product_name')->get();

        return view('orders.edit', compact('order','customers','products'));
    }

    /* ------------------------------------  PUT /orders/{order}  */
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'order_type'             => 'required|in:sale,purchase',
            'order_date'             => 'required|date',
            'delivery_date'          => 'nullable|date|after_or_equal:order_date',
            'items'                  => 'required|array|min:1',
            'items.*.product_id'     => 'required|exists:products,id',
            'items.*.amount'         => 'required|numeric|min:1',
            'items.*.unit_price'     => 'required|numeric|min:0',
            'situation'              => 'in:hazırlanıyor,tamamlandı',
            'is_paid'                => 'sometimes|boolean',
        ]);

        $total  = collect($data['items'])
                    ->reduce(fn($s,$i)=> $s + $i['amount'] * $i['unit_price'], 0);
        $isPaid = $request->boolean('is_paid');

        $order->update([
            'customer_id'   => Auth::user()->customer_id,
            'order_type'    => $data['order_type'],
            'order_date'    => $data['order_date'],
            'delivery_date' => $data['delivery_date'] ?? null,
            'situation'     => $data['situation'] ?? $order->situation,
            'total_amount'  => $total,
            'is_paid'       => $isPaid,
            'paid_at'       => $isPaid ? now() : null,
        ]);

        /* pivot sync */
        $order->products()->sync([]);
        foreach ($data['items'] as $item) {
            $order->products()->attach(
                $item['product_id'],
                ['amount'=>$item['amount'],'unit_price'=>$item['unit_price']]
            );
        }

        return redirect()->route('orders.index')
                         ->with('success', 'Order updated successfully.');
    }

    /* ------------------------------------  DELETE /orders/{order}  */
    public function destroy(Order $order)
    {
        $order->products()->detach();
        $order->delete();

        return redirect()->route('orders.index')
                         ->with('success', 'Order deleted successfully.');
    }
}
