<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;

class OrderController extends Controller
{
    // GET /orders
    public function index()
    {
        $orders = Order::with('customer')->orderBy('Order_Date','desc')->get();
        return view('orders.index', compact('orders'));
    }

    // GET /orders/create
    public function create()
    {
        $customers = Customer::orderBy('customer_name')->get();
        $products  = Product::orderBy('Product_name')->get();
        return view('orders.create', compact('customers','products'));
    }

    // POST /orders
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'Order_Date'     => 'required|date',
            'Delivery_date'  => 'nullable|date|after_or_equal:Order_Date',
            'items'          => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.amount'     => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // hesapla toplam
        $total = 0;
        foreach($data['items'] as $item){
            $total += $item['amount'] * $item['unit_price'];
        }
        $order = Order::create([
            'customer_id'   => $data['customer_id'],
            'Order_Date'    => $data['Order_Date'],
            'Delivery_date' => $data['Delivery_date'] ?? null,
            'total_amount'  => $total,
        ]);

        // pivot ekle
        foreach($data['items'] as $item){
            $order->products()->attach(
                $item['product_id'],
                ['amount'=>$item['amount'],'unit_price'=>$item['unit_price']]
            );
        }

        return redirect()->route('orders.index')
            ->with('success','Order created successfully.');
    }

    // GET /orders/{order}
    public function show(Order $order)
    {
        $order->load(['customer','products']);
        return view('orders.show', compact('order'));
    }

    // GET /orders/{order}/edit
    public function edit(Order $order)
    {
        $order->load('products');
        $customers = Customer::orderBy('customer_name')->get();
        $products  = Product::orderBy('Product_name')->get();
        return view('orders.edit', compact('order','customers','products'));
    }

    // PUT /orders/{order}
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'Order_Date'     => 'required|date',
            'Delivery_date'  => 'nullable|date|after_or_equal:Order_Date',
            'items'          => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.amount'     => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $total = 0;
        foreach($data['items'] as $item){
            $total += $item['amount'] * $item['unit_price'];
        }

        $order->update([
            'customer_id'   => $data['customer_id'],
            'Order_Date'    => $data['Order_Date'],
            'Delivery_date' => $data['Delivery_date'] ?? null,
            'total_amount'  => $total,
        ]);

        // pivot yeniden oluştur
        $order->products()->detach();
        foreach($data['items'] as $item){
            $order->products()->attach(
                $item['product_id'],
                ['amount'=>$item['amount'],'unit_price'=>$item['unit_price']]
            );
        }

        return redirect()->route('orders.index')
            ->with('success','Order updated successfully.');
    }

    // DELETE /orders/{order}
    public function destroy(Order $order)
    {
        $order->products()->detach();
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success','Order deleted successfully.');
    }
}
