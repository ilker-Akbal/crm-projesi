<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\ProductStock;
use App\Models\ProductPrice;

class ProductController extends Controller
{
    // GET /products
    public function index()
    {
        $products = Product::with('customer')
            ->orderBy('product_name')
            ->get();

        return view('products.index', compact('products'));
    }

    // GET /products/create
   public function create()
{
    $customers = Customer::orderBy('customer_name')->get();

    return view('products.create', compact('customers'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'product_name'     => 'required|string|max:255',
        'customer_id'      => 'nullable|exists:customers,id',
        'explanation'      => 'nullable|string',

        // 𐄂 yeni alanlar
        'stock_quantity'   => 'required|integer|min:0',
        'price'            => 'required|numeric|min:0',
    ]);

    DB::transaction(function () use ($data) {

        /* 1️⃣  ürün kaydı */
        $product = Product::create([
            'product_name' => $data['product_name'],
            'customer_id'  => $data['customer_id'] ?? null,
            'explanation'  => $data['explanation'] ?? null,
            'created_by'   => auth()->id(),
        ]);

        /* 2️⃣  ilk stok */
        ProductStock::create([
            'product_id'     => $product->id,
            'stock_quantity' => $data['stock_quantity'],
            'update_date'    => now(),
            'updated_by'     => auth()->id(),
        ]);

        /* 3️⃣  ilk fiyat */
        ProductPrice::create([
            'product_id' => $product->id,
            'price'      => $data['price'],
            'updated_by' => auth()->id(),
        ]);
    });

    return redirect()->route('products.index')
                     ->with('success', 'Product, stock and price created successfully!');
}

    // GET /products/{product}
    public function show(Product $product)
    {
        $product->load('customer','stocks','prices');
        return view('products.show', compact('product'));
    }

    // GET /products/{product}/edit
    public function edit(Product $product)
    {
        $customers = Customer::orderBy('customer_name')->get();
        return view('products.edit', compact('product','customers'));
    }

    // PUT /products/{product}
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'product_name' => 'required|string|max:255',
            'customer_id'  => 'nullable|exists:customers,id',
            'explanation'  => 'nullable|string',
        ]);

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success','Product updated successfully.');
    }

    // DELETE /products/{product}
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success','Product deleted successfully.');
    }
}
