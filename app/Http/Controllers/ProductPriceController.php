<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\ProductPrice;   // Gerçek veritabanına geçtiğinizde açın
// use App\Models\Product;

class ProductPriceController extends Controller
{
    /** GET /product_prices  */
    public function index()
    {
        // $productPrices = ProductPrice::with('product')->get();
        $productPrices = collect();          // Şimdilik boş veri
        return view('product_prices.index', compact('productPrices'));
    }

    /** GET /product_prices/create */
    public function create()
    {
        // $products = Product::orderBy('product_name')->get();
        $products = collect();               // Şimdilik boş
        return view('product_prices.create', compact('products'));
    }

    /** POST /product_prices  */
    public function store(Request $request)
    {
        // Validasyon + kayıt işlemi sonra eklenecek
        return back()->with('success', 'Demo: veri kaydedilmedi, sadece sayfa yüklendi.');
    }

    /* Diğer metodlar (show/edit/update/destroy) şimdilik boş bırakıldı */
    public function show($id)    {}
    public function edit($id)    {}
    public function update(Request $r, $id) {}
    public function destroy($id) {}
}
