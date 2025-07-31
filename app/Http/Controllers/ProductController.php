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
use Illuminate\Validation\Rule;

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
 * ------------------------------------------------*/

/* -------------------------------------------------
 |  PUT /products/{product}  →  Güncelle   (B yöntemi)
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

        /* ── seçilen seri numaraları ── */
        'blocked_serials'   => 'array',
        'blocked_serials.*' => [
            'string',
            Rule::exists('product_serials', 'serial_number')
                ->where('product_id', $product->id)
                ->where('status', ProductSerial::AVAILABLE),
        ],
    ];

    /* ---------- 2) Önceki / yeni değerler ---------- */
    $prevBlocked  = optional($product->stocks->last())->blocked_stock  ?? 0;
    $prevReserved = optional($product->stocks->last())->reserved_stock ?? 0;
    $prevTotal    = optional($product->stocks->last())->stock_quantity ?? 0;

    $newBlocked   = (int) $request->blocked_stock;
    $newReserved  = (int) $request->reserved_stock;
    $newTotal     = $request->filled('stock_quantity')
                   ? (int) $request->stock_quantity
                   : $prevTotal;

    $blockDiff   = max(0, $newBlocked  - $prevBlocked);   // ↑ artış kadar
    $reserveDiff = max(0, $newReserved - $prevReserved);
    $addedQty    = max(0, $newTotal - $prevTotal);

    /* ---------- 3) Validasyon ---------- */
    $data           = $request->validate($rules);
    $selectedSerials = collect($data['blocked_serials'] ?? [])
                       ->filter()->unique()->values()->all();

    /* ---------- 4) Transaction ---------- */
    DB::transaction(function () use (
        $product, $data, $request,
        $blockDiff, $reserveDiff,
        $newTotal, $selectedSerials,
        $prevBlocked, $prevReserved, $prevTotal
    ) {
        /* 4a ─ Ürün bilgileri */
        $product->update([
            'product_name' => $data['product_name'],
            'explanation'  => $data['explanation'] ?? $product->explanation,
            'updated_by'   => Auth::id(),
        ]);

        /* 4b ─ Fiyat (değiştiyse) */
        if ($request->filled('price')) {
            ProductPrice::create([
                'product_id' => $product->id,
                'price'      => $data['price'],
                'updated_by' => Auth::id(),
            ]);
        }

        /* 4c ─ Bloke / rezerve değişikliği ⇒ yeni stok satırı
                ❗  Stok miktarı değişikliği burada **kaydedilmez** (B yöntemi) */
        $blockedChanged  = $request->filled('blocked_stock')  && $data['blocked_stock']  != $prevBlocked;
        $reservedChanged = $request->filled('reserved_stock') && $data['reserved_stock'] != $prevReserved;

        if ($blockedChanged || $reservedChanged) {
            ProductStock::create([
                'product_id'     => $product->id,
                'stock_quantity' => $prevTotal,   // miktar aynen kalır
                'blocked_stock'  => $blockedChanged  ? $data['blocked_stock']  : $prevBlocked,
                'reserved_stock' => $reservedChanged ? $data['reserved_stock'] : $prevReserved,
                'update_date'    => now(),
                'updated_by'     => Auth::id(),
            ]);
        }

        /* 4d ─ Seri numarası durum güncellemeleri */

        // i) Kullanıcının seçtiği seri numaralarını BLOKED yap
        if ($selectedSerials) {
            ProductSerial::where('product_id', $product->id)
                         ->whereIn('serial_number', $selectedSerials)
                         ->update(['status' => ProductSerial::BLOCKED]);
        }

        // ii) Kalan diff’i otomatik tamamla
        $remaining = $blockDiff - count($selectedSerials);
        if ($remaining > 0) {
            $autoIds = $product->serials()
                               ->where('status', ProductSerial::AVAILABLE)
                               ->whereNotIn('serial_number', $selectedSerials)
                               ->limit($remaining)
                               ->pluck('id');
            ProductSerial::whereIn('id', $autoIds)
                         ->update(['status' => ProductSerial::BLOCKED]);
        }

        // iii) Rezerve artışı
        if ($reserveDiff > 0) {
            $ids = $product->serials()
                           ->where('status', ProductSerial::AVAILABLE)
                           ->limit($reserveDiff)
                           ->pluck('id');
            ProductSerial::whereIn('id', $ids)
                         ->update(['status' => ProductSerial::RESERVED]);
        }
    });

    /* ---------- 5) Redirect ---------- */
    return $addedQty > 0
        ? redirect()->route('products.serials.create',
                            ['product' => $product->id, 'added' => $addedQty])
                    ->with('info', "$addedQty adet yeni seri numarası giriniz.")
        : redirect()->route('products.index')
                    ->with('success', 'Ürün güncellendi.');
}







public function createSerials(Request $request, Product $product)
{
    // 0) Yetki: sadece kendi müşterinizin ürünü
    $this->authorizeProduct($product);

    // 1) Parametreleri oku
    $orderId = $request->query('order');          // siparişten mi geldik?
    $added   = (int) $request->query('added', 0);  // stok güncellemede eklenen miktar

    // 2) Sipariş rezervasyonu senaryosu
    if ($orderId) {
        // siparişi, pivot üzerinden miktarıyla birlikte yükle
        $order = Order::with('products')->findOrFail($orderId);
        $pivot = $order->products()
                       ->where('product_id', $product->id)
                       ->firstOrFail()
                       ->pivot;
        // daha önce girilmiş seri sayısı
        $existing = ProductSerial::where('order_id', $orderId)
                                 ->where('product_id', $product->id)
                                 ->count();
        // kalan girilmesi gereken adedi hesapla
        $remaining = $pivot->amount - $existing;
        abort_if($remaining <= 0,
            404,
            'Bu ürün için gerekli seri numaralarının tamamı zaten girilmiş.'
        );

        return view('products.serials_create', [
            'product' => $product,
            'order'   => $order,
            'qty'     => $remaining,
            'added'   => $remaining, // form’da expected_qty olarak da kullanabilirsiniz
        ]);
    }

    // 3) Yeni ürün oluşturma senaryosu (ilk kez stok giriyoruz)
    if ($added === 0) {
        // ilk stok miktarı
        $initial = optional($product->stocks()->latest('id')->first())
                   ->stock_quantity ?? 0;
        abort_if($initial <= 0,
            404,
            'Bu ürün için girilecek seri numarası bulunmuyor.'
        );

        return view('products.serials_create', [
            'product' => $product,
            'order'   => null,
            'qty'     => $initial,
            'added'   => 0,
        ]);
    }

    // 4) Stok güncelleme senaryosu (mevcut ürüne yeni qty ekliyoruz)
    return view('products.serials_create', [
        'product' => $product,
        'order'   => null,
        'qty'     => $added,
        'added'   => $added,
    ]);
}

public function storeSerials(Request $request, Product $product)
{
    $this->authorizeProduct($product);

    $orderId     = $request->input('order_id');
    $expectedQty = $request->filled('expected_qty')
                 ? (int) $request->input('expected_qty')
                 : null;

    if ($orderId) {
        // satın-alma rezervasyonunda kalan miktarı tekrar hesapla
        $pivot    = Order::findOrFail($orderId)
                         ->products()
                         ->findOrFail($product->id)
                         ->pivot;
        $existing = ProductSerial::where('order_id', $orderId)
                                 ->where('product_id', $product->id)
                                 ->count();
        $qty = $pivot->amount - $existing;
    }
    elseif ($expectedQty !== null && $expectedQty > 0) {
        $qty = $expectedQty;
    }
    else {
        // yeni ürün senaryosu
        $qty = $product->stocks()->latest('id')->value('stock_quantity') ?? 0;
    }

    $data = $request->validate([
        'serials'   => "required|array|size:$qty",
        'serials.*' => 'required|string|distinct|unique:product_serials,serial_number',
    ],[
        'serials.size' => "Lütfen tam $qty adet seri numarası girin."
    ]);

    DB::transaction(function () use ($data, $product, $orderId, $expectedQty, $qty) {
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

        // sadece stok güncelleme senaryosunda stoğu artır
        if (is_null($orderId) && $expectedQty !== null && $expectedQty > 0) {
            resolve(\App\Http\Controllers\OrderController::class)
                ->moveStock($product->id, $expectedQty);
        }
    });

    if ($orderId) {
        return redirect()
            ->route('orders.show', $orderId)
            ->with('success', 'Seri numaraları girildi ve rezerve edildi.');
    }

    return redirect()
        ->route('products.show', $product)
        ->with('success', 'Seri numaraları girildi ve stok güncellendi.');
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
