<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Product;   // Gerçek veri ekleyince açın
// use App\Models\Customer;  // 〃

class ProductController extends Controller
{
    /** GET /products ─ Liste */
    public function index()
    {
        // $products = Product::with('customer')->get();
        $products = collect();                 // şimdilik boş koleksiyon
        return view('products.index', compact('products'));
    }

    /** GET /products/create ─ Form */
    public function create()
    {
        // $customers = Customer::orderBy('customer_name')->get();
        $customers = collect();                // şimdilik boş
        return view('products.create', compact('customers'));
    }

    /** POST /products ─ demo */
    public function store(Request $request)
    {
        return back()->with('success', 'Demo aşamasında veri kaydedilmiyor.');
    }

    /* Diğer CRUD metodları ileride doldurulabilir */
    public function show($id)    {}
    public function edit($id)    {}
    public function update(Request $r, $id) {}
    public function destroy($id) {}
}
