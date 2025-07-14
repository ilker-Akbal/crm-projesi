<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductStock;
use App\Models\Product;

class ProductStockController extends Controller
{
    // GET /product_stocks
    public function index()
    {
        $productStocks = ProductStock::with('product')
            ->orderBy('update_date','desc')
            ->get();

        return view('product_stocks.index', compact('productStocks'));
    }

    // GET /product_stocks/create
    public function create()
    {
        $products = Product::orderBy('product_name')->get();
        return view('product_stocks.create', compact('products'));
    }

    // POST /product_stocks
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'     => 'required|exists:products,id',
            'stock_quantity' => 'required|numeric|min:0',
            'update_date'    => 'required|date',
        ]);

        ProductStock::create($data);

        return redirect()->route('product_stocks.index')
            ->with('success','Stock entry added.');
    }
}
