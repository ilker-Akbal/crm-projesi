<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;

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

    // POST /products
    public function store(Request $request)
{
    $data = $request->validate([
        'product_name' => 'required|string|max:255',
        'customer_id'  => 'nullable|exists:customers,id',
        'explanation'  => 'nullable|string',
    ]);

    // İsteğe bağlı created_by / updated_by
    $data['created_by'] = $data['updated_by'] = auth()->id() ?? 1;

    Product::create($data);

    return redirect()
        ->route('products.index')
        ->with('success', 'Product created successfully.');
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
