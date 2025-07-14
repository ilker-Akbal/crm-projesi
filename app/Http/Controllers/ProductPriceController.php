<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductPrice;
use App\Models\Product;

class ProductPriceController extends Controller
{
    // GET /product_prices
    public function index()
    {
        $productPrices = ProductPrice::with('product')
            ->orderBy('updated_at','desc')
            ->get();

        return view('product_prices.index', compact('productPrices'));
    }

    // GET /product_prices/create
    public function create()
    {
        $products = Product::orderBy('product_name')->get();
        return view('product_prices.create', compact('products'));
    }

    // POST /product_prices
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'price'      => 'required|numeric|min:0',
            'updated_at' => 'required|date',
        ]);

        ProductPrice::create($data);

        return redirect()->route('product_prices.index')
            ->with('success','Price added successfully.');
    }
}
