<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductPrice;
use App\Models\Product;

class ProductPriceController extends Controller
{
    /* ----------------------------------------------
     |  GET /product_prices  →  Liste
     * --------------------------------------------*/
    public function index()
    {
        $productPrices = ProductPrice::whereHas(
                'product',
                fn ($q) => $q->where('customer_id', Auth::user()->customer_id)
            )
            ->with('product')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('product_prices.index', compact('productPrices'));
    }

    /* ----------------------------------------------
     |  GET /product_prices/create  →  Form
     * --------------------------------------------*/
    public function create()
    {
        $products = Product::where('customer_id', Auth::user()->customer_id)
                           ->orderBy('product_name')
                           ->get();

        return view('product_prices.create', compact('products'));
    }

    /* ----------------------------------------------
     |  POST /product_prices  →  Kaydet
     * --------------------------------------------*/
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'price'      => 'required|numeric|min:0',
            'updated_at' => 'required|date',
        ]);

        // Seçilen ürün gerçekten giriş yapan müşteriye mi ait?
        if (! Product::where('id', $data['product_id'])
                     ->where('customer_id', Auth::user()->customer_id)
                     ->exists()) {
            abort(403, 'Bu ürüne fiyat ekleme yetkiniz yok.');
        }

        ProductPrice::create($data + ['updated_by' => Auth::id()]);

        return redirect()->route('product_prices.index')
                         ->with('success', 'Price added successfully.');
    }
}
