<?php
// app/Http/Controllers/ProductStockController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductStock;
use Carbon\Carbon;

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
    $request->validate([
        'product_id'      => 'required|exists:products,id',
        'stock_quantity'  => 'required|integer|min:0',
        'blocked_stock'   => 'nullable|integer|min:0',
        'reserved_stock'  => 'nullable|integer|min:0',
        'update_date'     => 'required|date',
    ]);

    $productStock = new ProductStock();
    $productStock->product_id      = $request->product_id;
    $productStock->stock_quantity  = $request->stock_quantity;
    $productStock->blocked_stock   = $request->blocked_stock ?? 0;
    $productStock->reserved_stock  = $request->reserved_stock ?? 0;
    $productStock->update_date     = Carbon::parse($request->update_date); // ⬅️ BURASI ÖNEMLİ
    $productStock->save();

    return redirect()->route('product_stocks.index')->with('success', 'Stok başarıyla eklendi.');
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
    $request->validate([
        'product_id'      => 'required|exists:products,id',
        'stock_quantity'  => 'required|integer|min:0',
        'blocked_stock'   => 'nullable|integer|min:0',
        'reserved_stock'  => 'nullable|integer|min:0',
        'update_date'     => 'required|date',
    ]);

    $productStock->product_id      = $request->product_id;
    $productStock->stock_quantity  = $request->stock_quantity;
    $productStock->blocked_stock   = $request->blocked_stock ?? 0;
    $productStock->reserved_stock  = $request->reserved_stock ?? 0;
    $productStock->update_date     = Carbon::parse($request->update_date); // ⬅️ BURASI
    $productStock->save();

    return redirect()->route('product_stocks.index')->with('success', 'Stok başarıyla güncellendi.');
}
}
