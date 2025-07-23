<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductStock;

class ProductStockController extends Controller
{
  // Liste
  public function index()
  {
    $productStocks = ProductStock::with('product')
      ->whereHas('product', fn($q)=> $q->where('customer_id', Auth::user()->customer_id))
      ->orderByDesc('update_date')
      ->get();

    return view('product_stocks.index', compact('productStocks'));
  }

  // Form
  public function create()
  {
    $products = Product::where('customer_id', Auth::user()->customer_id)
                       ->orderBy('product_name')
                       ->get();

    return view('product_stocks.create', compact('products'));
  }

  // Kaydet
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
}
