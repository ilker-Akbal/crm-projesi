<?php
// app/Http/Controllers/ProductStockController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductStock;

class ProductStockController extends Controller
{
    // Liste ve filtreleme
    public function index(Request $request)
    {
        // Temel sorgu: oturumlu kullanıcının ürün stokları
        $query = ProductStock::with('product')
            ->whereHas('product', fn($q) =>
                $q->where('customer_id', Auth::user()->customer_id)
            );

        // Filtre: ürün seçimi
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filtre: başlangıç/bitiş tarihleri
        if ($request->filled('start_date')) {
            $query->whereDate('update_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('update_date', '<=', $request->end_date);
        }

        // Sorgu sonucunu al
        $productStocks = $query
            ->orderByDesc('update_date')
            ->get();

        // Ürün dropdown verisi
        $products = Product::where('customer_id', Auth::user()->customer_id)
            ->orderBy('product_name')
            ->get();

        return view('product_stocks.index', compact('productStocks', 'products'));
    }

    // Yeni stok ekleme formu
    public function create()
    {
        $products = Product::where('customer_id', Auth::user()->customer_id)
                           ->orderBy('product_name')
                           ->get();

        return view('product_stocks.create', compact('products'));
    }

    // Stok kaydet
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'     => 'required|exists:products,id',
            'stock_quantity' => 'required|integer|min:0',
            'blocked_stock'  => 'nullable|integer|min:0|max:'.$request->stock_quantity,
            'reserved_stock' => 'nullable|integer|min:0|max:'.($request->stock_quantity - $request->blocked_stock),
            'update_date'    => 'required|date',
        ]);

        ProductStock::create([
            'product_id'     => $data['product_id'],
            'stock_quantity' => $data['stock_quantity'],
            'blocked_stock'  => $data['blocked_stock']  ?? 0,
            'reserved_stock' => $data['reserved_stock'] ?? 0,
            'update_date'    => $data['update_date'],
            'updated_by'     => Auth::id(),
        ]);

        return redirect()->route('product_stocks.index')
                         ->with('success','Stok başarıyla eklendi.');
    }

    // Düzenleme formu
    public function edit(ProductStock $productStock)
    {
        if ($productStock->product->customer_id !== Auth::user()->customer_id) {
            abort(403);
        }

        $products = Product::where('customer_id', Auth::user()->customer_id)
                           ->orderBy('product_name')
                           ->get();

        return view('product_stocks.edit', compact('productStock', 'products'));
    }

    // Güncelle
    public function update(Request $request, ProductStock $productStock)
    {
        if ($productStock->product->customer_id !== Auth::user()->customer_id) {
            abort(403);
        }

        $data = $request->validate([
            'product_id'     => 'required|exists:products,id',
            'stock_quantity' => 'required|integer|min:0',
            'blocked_stock'  => 'nullable|integer|min:0|max:'.$request->stock_quantity,
            'reserved_stock' => 'nullable|integer|min:0|max:'.($request->stock_quantity - $request->blocked_stock),
            'update_date'    => 'required|date',
        ]);

        $productStock->update([
            'product_id'     => $data['product_id'],
            'stock_quantity' => $data['stock_quantity'],
            'blocked_stock'  => $data['blocked_stock']  ?? 0,
            'reserved_stock' => $data['reserved_stock'] ?? 0,
            'update_date'    => $data['update_date'],
            'updated_by'     => Auth::id(),
        ]);

        return redirect()->route('product_stocks.index')
                         ->with('success','Stok başarıyla güncellendi.');
    }
}
