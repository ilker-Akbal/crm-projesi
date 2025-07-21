<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductStock;
use App\Models\Product;

class ProductStockController extends Controller
{
    /* ----------------------------------------------
     |  GET /product_stocks  →  Liste
     * --------------------------------------------*/
    public function index()
    {
        $productStocks = ProductStock::whereHas(
                'product',
                fn ($q) => $q->where('customer_id', Auth::user()->customer_id)
            )
            ->with('product')
            ->orderBy('update_date', 'desc')
            ->get();

        return view('product_stocks.index', compact('productStocks'));
    }

    /* ----------------------------------------------
     |  GET /product_stocks/create  →  Form
     * --------------------------------------------*/
    public function create()
    {
        $products = Product::where('customer_id', Auth::user()->customer_id)
                           ->orderBy('product_name')
                           ->get();

        return view('product_stocks.create', compact('products'));
    }

    /* ----------------------------------------------
     |  POST /product_stocks  →  Kaydet
     * --------------------------------------------*/
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'     => 'required|exists:products,id',
            'stock_quantity' => 'required|numeric|min:0',
            'update_date'    => 'required|date',
        ]);

        // Ürün gerçekten sizin müşterinize mi ait?
        if (! Product::where('id', $data['product_id'])
                     ->where('customer_id', Auth::user()->customer_id)
                     ->exists()) {
            abort(403, 'Bu ürüne stok ekleme yetkiniz yok.');
        }

        ProductStock::create($data + ['updated_by' => Auth::id()]);

        return redirect()->route('product_stocks.index')
                         ->with('success', 'Stock entry added.');
    }
}
