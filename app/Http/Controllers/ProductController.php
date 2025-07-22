<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductPrice;

class ProductController extends Controller
{
    /* -------------------------------------------------
     |  GET /products  →  Liste
     * ------------------------------------------------*/
    public function index()
    {
        $products = Product::where('customer_id', Auth::user()->customer_id)
                           ->with(['customer', 'stocks', 'prices'])
                           ->orderBy('product_name')
                           ->get();

        return view('products.index', compact('products'));
    }

    /* -------------------------------------------------
     |  GET /products/create  →  Form
     * ------------------------------------------------*/
    public function create()
    {
        // müşteri seçimi yok; ürün doğrudan giriş yapan müşteriye bağlı
        return view('products.create');
    }

    /* -------------------------------------------------
     |  POST /products  →  Kaydet
     * ------------------------------------------------*/
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_name'   => 'required|string|max:255',
            'explanation'    => 'nullable|string',
            'stock_quantity' => 'required|integer|min:0',
            'price'          => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($data) {
            $product = Product::create([
                'product_name' => $data['product_name'],
                'customer_id'  => Auth::user()->customer_id,
                'explanation'  => $data['explanation'] ?? null,
                'created_by'   => Auth::id(),
            ]);

            ProductStock::create([
                'product_id'     => $product->id,
                'stock_quantity' => $data['stock_quantity'],
                'update_date'    => now(),
                'updated_by'     => Auth::id(),
            ]);

            ProductPrice::create([
                'product_id' => $product->id,
                'price'      => $data['price'],
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()->route('products.index')
                         ->with('success', 'Product, stock and price created successfully!');
    }

    /* -------------------------------------------------
     |  GET /products/{product}  →  Detay
     * ------------------------------------------------*/
    public function show(Product $product)
    {
        $this->authorizeProduct($product);

        $product->load('customer', 'stocks', 'prices');

        return view('products.show', compact('product'));
    }

    /* -------------------------------------------------
     |  GET /products/{product}/edit  →  Form
     * ------------------------------------------------*/
    public function edit(Product $product)
    {
        $this->authorizeProduct($product);

        return view('products.edit', compact('product'));
    }

    /* -------------------------------------------------
     |  PUT /products/{product}  →  Güncelle
     * ------------------------------------------------*/
    public function update(Request $request, Product $product)
{
    $this->authorizeProduct($product);

    $data = $request->validate([
        'product_name'   => 'required|string|max:255',
        'explanation'    => 'nullable|string',
        'stock_quantity' => 'nullable|integer|min:0',
        'price'          => 'nullable|numeric|min:0',
    ]);

    DB::transaction(function () use ($data, $product) {
        // Ürünü güncelle
        $product->update([
            'product_name' => $data['product_name'],
            'explanation'  => $data['explanation'] ?? $product->explanation,
            'updated_by'   => Auth::id(),
        ]);

        // Stok varsa güncelle, yoksa oluştur
        if (!is_null($data['stock_quantity'])) {
            $stock = ProductStock::where('product_id', $product->id)->first();

            if ($stock) {
                $stock->update([
                    'stock_quantity' => $data['stock_quantity'],
                    'update_date'    => now(),
                    'updated_by'     => Auth::id(),
                ]);
            } else {
                ProductStock::create([
                    'product_id'     => $product->id,
                    'stock_quantity' => $data['stock_quantity'],
                    'update_date'    => now(),
                    'updated_by'     => Auth::id(),
                ]);
            }
        }

        // Fiyat varsa güncelle, yoksa oluştur
        if (!is_null($data['price'])) {
            $price = ProductPrice::where('product_id', $product->id)->first();

            if ($price) {
                $price->update([
                    'price'      => $data['price'],
                    'updated_by' => Auth::id(),
                ]);
            } else {
                ProductPrice::create([
                    'product_id' => $product->id,
                    'price'      => $data['price'],
                    'updated_by' => Auth::id(),
                ]);
            }
        }
    });

    return redirect()->route('products.index')
                     ->with('success', 'Product updated successfully.');
}


    /* -------------------------------------------------
     |  DELETE /products/{product}  →  Sil
     * ------------------------------------------------*/
    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Product deleted successfully.');
    }

    /* -------------------------------------------------
     |  Yardımcı: ürün sahibi mi?
     * ------------------------------------------------*/
    private function authorizeProduct(Product $product): void
    {
        if ($product->customer_id !== Auth::user()->customer_id) {
            abort(403, 'Bu ürüne erişim yetkiniz yok.');
        }
    }
}
