<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Order;     // İleride açabilirsiniz
// use App\Models\Customer;  // 〃
// use App\Models\Product;   // 〃

class OrderController extends Controller
{
    /**  GET /orders  – Liste sayfası  */
    public function index()
    {
        // $orders = Order::with('customer')->get();   // Gerçek sorgu
        $orders = collect();                          // Şimdilik boş

        return view('orders.index', compact('orders'));
    }

    /**  GET /orders/create  – Form sayfası  */
    public function create()
    {
        // $customers = Customer::orderBy('customer_name')->get();
        // $products  = Product::with('price')->get();
        $customers = collect();   // Şimdilik boş
        $products  = collect();   // Şimdilik boş

        return view('orders.create', compact('customers', 'products'));
    }

    /**  POST /orders  – Henüz gerçek kayıt yok, sadece geri dön */
    public function store(Request $request)
    {
        return back()->with('success', 'Bu demo aşamasında veri kaydedilmiyor.');
    }

    /* Diğer CRUD metodları ileride doldurulacak */
    public function show($id)    {}
    public function edit($id)    {}
    public function update(Request $r, $id) {}
    public function destroy($id) {}
}
