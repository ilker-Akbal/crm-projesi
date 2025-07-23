<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductPrice;
use App\Models\ProductSerial;

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
    // 1) Validasyonu serial_number olmadan güncelle
    $data = $request->validate([
        'product_name'   => 'required|string|max:255',
        'explanation'    => 'nullable|string',
        'stock_quantity' => 'required|integer|min:1',
        'blocked_stock'  => 'nullable|integer|min:0|max:'.$request->stock_quantity,
        'reserved_stock' => 'nullable|integer|min:0|max:'.($request->stock_quantity - $request->blocked_stock),
        'price'          => 'required|numeric|min:0',
    ]);

    // 2) Ürünü, stok ve fiyatı oluştur
    DB::transaction(function () use ($data, &$product) {
        $product = Product::create([
            'product_name'  => $data['product_name'],
            'customer_id'   => Auth::user()->customer_id,
            'explanation'   => $data['explanation'] ?? null,
            'created_by'    => Auth::id(),
        ]);

        ProductStock::create([
            'product_id'     => $product->id,
            'stock_quantity' => $data['stock_quantity'],
            'blocked_stock'  => $data['blocked_stock']  ?? 0,
            'reserved_stock' => $data['reserved_stock'] ?? 0,
            'update_date'    => now(),
            'updated_by'     => Auth::id(),
        ]);

        ProductPrice::create([
            'product_id' => $product->id,
            'price'      => $data['price'],
            'updated_by' => Auth::id(),
        ]);
    });

    // 3) Seri numarası girişi sayfasına yönlendir
    return redirect()
           ->route('products.serials.create', $product)
           ->with('info','Ürün oluşturuldu; lütfen seri no girin.');
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
        'blocked_stock'  => 'nullable|integer|min:0|max:'.$request->stock_quantity,
        'reserved_stock' => 'nullable|integer|min:0|max:'.($request->stock_quantity - $request->blocked_stock),
        'price'          => 'nullable|numeric|min:0',
    ]);

    DB::transaction(function () use ($data, $product) {
        // → Ürün güncelleme: artık sadece name ve explanation
        $product->update([
            'product_name' => $data['product_name'],
            'explanation'  => $data['explanation'] ?? $product->explanation,
            'updated_by'   => Auth::id(),
        ]);

        // Stok varsa güncelle, yoksa oluştur
       if (!is_null($data['stock_quantity'])) {
    $stock = ProductStock::firstOrNew(['product_id' => $product->id]);

    $stock->fill([
        'stock_quantity' => $data['stock_quantity'],
        'blocked_stock'  => $data['blocked_stock']  ?? $stock->blocked_stock ?? 0, // ←
        'reserved_stock' => $data['reserved_stock'] ?? $stock->reserved_stock ?? 0, // ←
        'update_date'    => now(),
        'updated_by'     => Auth::id(),
    ])->save();
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
                     ->with('success','Product updated successfully.');
}

public function createSerials(Product $product)
{
    $this->authorizeProduct($product);
    $qty = $product->stocks()->latest('id')->value('stock_quantity') ?? 0;
    return view('products.serials_create', compact('product','qty'));
}

// Seri numaralarını kaydeden metod
public function storeSerials(Request $request, Product $product)
{
    $this->authorizeProduct($product);
    $qty = $product->stocks()->latest('id')->value('stock_quantity') ?? 0;

    $data = $request->validate([
        'serials'   => "required|array|size:$qty",
        'serials.*' => 'required|string|distinct|unique:product_serials,serial_number',
    ],[
        'serials.size' => "Lütfen tam $qty adet seri numarası girin."
    ]);

    DB::transaction(function() use($data,$product){
        foreach($data['serials'] as $sn){
            ProductSerial::create([
                'product_id'    => $product->id,
                'serial_number' => $sn,
                'created_by'    => Auth::id(),
            ]);
        }
    });

    return redirect()
           ->route('products.index')
           ->with('success','Tüm seri numaraları kaydedildi.');
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
