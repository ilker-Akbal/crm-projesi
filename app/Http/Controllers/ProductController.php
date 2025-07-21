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
    public function index()
    {
        $products = Product::with('customer')
            ->orderBy('product_name')
            ->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_name'     => 'required|string|max:255',
            'explanation'      => 'nullable|string',
            'stock_quantity'   => 'required|integer|min:0',
            'price'            => 'required|numeric|min:0',
        ]);

        $data['customer_id'] = auth()->user()->customer_id ?? null;

        DB::transaction(function () use ($data) {
            $product = Product::create([
                'product_name' => $data['product_name'],
                'customer_id'  => $data['customer_id'],
                'explanation'  => $data['explanation'] ?? null,
                'created_by'   => auth()->id(),
            ]);

            ProductStock::create([
                'product_id'     => $product->id,
                'stock_quantity' => $data['stock_quantity'],
                'update_date'    => now(),
                'updated_by'     => auth()->id(),
            ]);

            ProductPrice::create([
                'product_id' => $product->id,
                'price'      => $data['price'],
                'updated_by' => auth()->id(),
            ]);
        });

        return redirect()->route('products.index')
                         ->with('success', 'Product, stock and price created successfully!');
    }

    public function show(Product $product)
    {
        $product->load('customer','stocks','prices');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'product_name'   => 'required|string|max:255',
            'explanation'    => 'nullable|string',
            'stock_quantity' => 'nullable|integer|min:0',
            'price'          => 'nullable|numeric|min:0',
        ]);

        $data['customer_id'] = auth()->user()->customer_id ?? null;

        DB::transaction(function () use ($data, $product) {
            $product->update([
                'product_name' => $data['product_name'],
                'explanation'  => $data['explanation'] ?? $product->explanation,
                'customer_id'  => $data['customer_id'],
                'updated_by'   => auth()->id(),
            ]);

            if (!is_null($data['stock_quantity'])) {
                ProductStock::create([
                    'product_id'     => $product->id,
                    'stock_quantity' => $data['stock_quantity'],
                    'update_date'    => now(),
                    'updated_by'     => auth()->id(),
                ]);
            }

            if (!is_null($data['price'])) {
                ProductPrice::create([
                    'product_id' => $product->id,
                    'price'      => $data['price'],
                    'updated_by' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success','Product deleted successfully.');
    }
}
