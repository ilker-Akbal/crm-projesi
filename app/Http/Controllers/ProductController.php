<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductPrice;
use App\Models\ProductSerial;
use App\Models\Order;   //  ← EKLEYİN

class ProductController extends Controller
{
    /* -------------------------------------------------
     |  GET /products  →  Liste
     * ------------------------------------------------*/
    public function index()
{
    $products = Product::where('customer_id', Auth::user()->customer_id)
        ->with(['customer','stocks','prices','serials'])   // ← 'serials' eklendi
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
    $rules = [
    'product_name'   => 'required|string|max:255|unique:products,product_name',
    'explanation'    => 'nullable|string',
    'stock_quantity' => 'required|integer|min:1',
    'price'          => 'required|numeric|min:0',
];

$max = (int) $request->stock_quantity;   // çünkü “required”
$rules['blocked_stock']  = "nullable|integer|min:0|max:$max";
$rules['reserved_stock'] = [
    'nullable',
    'integer',
    'min:0',
    "max:$max",
    function ($attr, $value, $fail) use ($request, $max) {
        $blocked = (int) ($request->blocked_stock ?? 0);
        if ($value + $blocked > $max) {
            $fail('Rezerve + bloke toplamı stok miktarını aşamaz.');
        }
    },
];
    $data = $request->validate($rules);

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

    $product->load('customer','stocks','prices','serials');  // ← 'serials' eklendi

    return view('products.show', compact('product'));
}

    /* -------------------------------------------------
     |  GET /products/{product}/edit  →  Form
     * ------------------------------------------------*/
public function edit(Product $product)
{
    $this->authorizeProduct($product);

    // PLUCK YOK - tam ProductSerial modelleri geliyor
    $availableSerials = $product->serials()
     ->where('status', ProductSerial::AVAILABLE)
     ->get();
return view('products.edit', compact('product','availableSerials'));


}


    /* -------------------------------------------------
     |  PUT /products/{product}  →  Güncelle
     * ------------------------------------------------*//* -------------------------------------------------
 |  PUT /products/{product}  →  Güncelle
 * ------------------------------------------------*/
/* -------------------------------------------------
 |  PUT /products/{product}  →  Güncelle
 * ------------------------------------------------*/
public function update(Request $request, Product $product)
{
    $this->authorizeProduct($product);

    /* ---------- 1) Dinamik validasyon ---------- */
    $rules = [
        'product_name'   => 'required|string|max:255|unique:products,product_name,' . $product->id,
        'explanation'    => 'nullable|string',
        'price'          => 'nullable|numeric|min:0',
        'stock_quantity' => 'nullable|integer|min:0',
        'blocked_stock'  => 'nullable|integer|min:0',
        'reserved_stock' => 'nullable|integer|min:0',
    ];

    /* --- Bloke & Rezerve artış miktarları --- */
    $prevBlocked  = optional($product->stocks->last())->blocked_stock  ?? 0;
    $prevReserved = optional($product->stocks->last())->reserved_stock ?? 0;

    $newBlocked   = (int) $request->blocked_stock;
    $newReserved  = (int) $request->reserved_stock;

    $blockDiff    = max(0, $newBlocked   - $prevBlocked);
    $reserveDiff  = max(0, $newReserved  - $prevReserved);

    /* --- Bloke edilen seriler --- */
    if ($blockDiff > 0) {
        $rules['blocked_serials']   = "required|array|size:$blockDiff";
        $rules['blocked_serials.*'] = [
            'distinct',
            function ($attr, $value, $fail) use ($product) {
                $ok = ProductSerial::where('product_id', $product->id)
                      ->where('serial_number', $value)
                      ->where('status', ProductSerial::AVAILABLE)
                      ->exists();
                if (! $ok) $fail("Seri $value uygun değil.");
            },
        ];
    }

    /* --- Rezerve edilen seriler --- */
    if ($reserveDiff > 0) {
        $rules['reserved_serials']   = "required|array|size:$reserveDiff";
        $rules['reserved_serials.*'] = [
            'distinct',
            function ($attr, $value, $fail) use ($product) {
                $ok = ProductSerial::where('product_id', $product->id)
                      ->where('serial_number', $value)
                      ->where('status', ProductSerial::AVAILABLE)
                      ->exists();
                if (! $ok) $fail("Seri $value uygun değil.");
            },
        ];
    }

    $data = $request->validate($rules);

    /* ---------- 2) Transaction ---------- */
    DB::transaction(function () use ($data,
                                      $product,
                                      $request,
                                      $blockDiff,
                                      $reserveDiff) {

        /* 2a Ürün ana bilgileri */
        $product->update([
            'product_name' => $data['product_name'],
            'explanation'  => $data['explanation'] ?? $product->explanation,
            'updated_by'   => Auth::id(),
        ]);

        /* 2b Fiyat (opsiyonel) */
        if ($request->filled('price')) {
            ProductPrice::updateOrCreate(
                ['product_id' => $product->id],
                ['price' => $data['price'], 'updated_by' => Auth::id()]
            );
        }

        /* 2c Stok satırı gerekiyorsa */
        $hasStockInput = $request->filled('stock_quantity')
                       || $request->filled('blocked_stock')
                       || $request->filled('reserved_stock');

        if ($hasStockInput) {
            $last = $product->stocks->last();

            ProductStock::create([
                'product_id'     => $product->id,
                'stock_quantity' => $request->filled('stock_quantity')
                                            ? $data['stock_quantity'] : ($last->stock_quantity ?? 0),
                'blocked_stock'  => $request->filled('blocked_stock')
                                            ? $data['blocked_stock']  : ($last->blocked_stock  ?? 0),
                'reserved_stock' => $request->filled('reserved_stock')
                                            ? $data['reserved_stock'] : ($last->reserved_stock ?? 0),
                'update_date'    => now(),
                'updated_by'     => Auth::id(),
            ]);
        }

        /* 2d Seri durum güncellemeleri */
        if ($blockDiff > 0) {
            ProductSerial::where('product_id', $product->id)
                ->whereIn('serial_number', $data['blocked_serials'])
                ->update(['status' => ProductSerial::BLOCKED]);
        }

        if ($reserveDiff > 0) {
            ProductSerial::where('product_id', $product->id)
                ->whereIn('serial_number', $data['reserved_serials'])
                ->update(['status' => ProductSerial::RESERVED]);
        }
    });

    /* ---------- 3) Redirect ---------- */
    return redirect()->route('products.index')
                     ->with('success', 'Ürün güncellendi.');
}



public function createSerials(Request $request, Product $product)
{
    $this->authorizeProduct($product);

    /* ?order=... opsiyonel */
    $orderId = $request->query('order');

    /* ---------- 1) Stok girişi (order yok) ---------- */
    if (! $orderId) {
        // En son stok kaydı model olarak alınır → accessor çalışır
        $lastStock = $product->stocks()->latest('id')->first();
        $qty       = $lastStock?->available_stock ?? 0;

        abort_if($qty <= 0, 404, 'Bu ürün için eklenecek seri yok.');

        return view('products.serials_create', [
            'product' => $product,
            'order'   => null,
            'qty'     => $qty,
        ]);
    }

    /* ---------- 2) Sipariş senaryosu ---------- */
    $order = Order::with('products')->findOrFail($orderId);
    $pivot = $order->products()->whereKey($product->id)->firstOrFail()->pivot;
    $qty   = $pivot->amount;

    return view('products.serials_create', [
        'product' => $product,
        'order'   => $order,
        'qty'     => $qty,
    ]);
}





public function storeSerials(Request $request, Product $product)
{
    $this->authorizeProduct($product);

    $orderId = $request->input('order_id');   // null → stok işlemi
    $qty     = 0;

    /* --- 1) Adet hesapla --- */
    if ($orderId) {
        $pivot = Order::findOrFail($orderId)
                      ->products()
                      ->whereKey($product->id)
                      ->firstOrFail()
                      ->pivot;
        $qty = $pivot->amount;
    } else {
        // accessor çalışsın diye model nesnesi alınır
        $lastStock = $product->stocks()->latest('id')->first();
        $qty       = $lastStock?->available_stock ?? 0;
    }

    /* --- 2) Doğrulama --- */
    $data = $request->validate([
        'serials'   => "required|array|size:$qty",
        'serials.*' => 'required|string|distinct|unique:product_serials,serial_number',
    ],[
        'serials.size' => "Lütfen tam $qty adet seri numarası girin."
    ]);

    /* --- 3) Kayıt --- */
    DB::transaction(function () use ($data, $product, $orderId) {
        foreach ($data['serials'] as $sn) {
            ProductSerial::create([
                'order_id'      => $orderId,
                'product_id'    => $product->id,
                'serial_number' => $sn,
                'status'        => $orderId
                                    ? ProductSerial::RESERVED
                                    : ProductSerial::AVAILABLE,
                'created_by'    => Auth::id(),
            ]);
        }
    });

    /* --- 4) Yönlendirme --- */
    return $orderId
        ? redirect()->route('orders.show', $orderId)
                     ->with('success', 'Seri numaraları kaydedildi.')
        : redirect()->route('products.show', $product)
                     ->with('success', 'Seri numaraları kaydedildi.');
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
