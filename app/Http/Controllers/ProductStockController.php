<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\ProductStock;   // Gerçek veride açarsınız
// use App\Models\Product;

class ProductStockController extends Controller
{
    /** GET /product_stocks  */
    public function index()
    {
        // $productStocks = ProductStock::with('product')->get();
        $productStocks = collect();          // Şimdilik boş
        return view('product_stocks.index', compact('productStocks'));
    }

    /** GET /product_stocks/create */
    public function create()
    {
        // $products = Product::orderBy('product_name')->get();
        $products = collect();               // Şimdilik boş
        return view('product_stocks.create', compact('products'));
    }

    /** POST /product_stocks  (demo) */
    public function store(Request $request)
    {
        return back()->with('success', 'Demo aşamasında veri kaydedilmiyor.');
    }

    /* Diğer CRUD metodları sonra doldurulabilir */
    public function show($id)    {}
    public function edit($id)    {}
    public function update(Request $r, $id) {}
    public function destroy($id) {}
}
