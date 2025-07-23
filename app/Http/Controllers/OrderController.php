<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Customer;
use App\Models\Company;

class OrderController extends Controller
{
    /* -------- GET /orders ---------- */
    public function index()
    {
        $orders = Order::where('customer_id', Auth::user()->customer_id)
                       ->with(['customer','company'])
                       ->latest('order_date')
                       ->get();
        return view('orders.index', compact('orders'));
    }

    /* -------- GET /orders/create ---------- */
    public function create()
    {
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();
        $companies = Company::where('customer_id', Auth::user()->customer_id)
                            ->orderBy('Company_name')->get();

        $products = Product::where('customer_id', Auth::user()->customer_id)
            ->with([
                'prices'=>fn($q)=>$q->latest()->limit(1),
                'stocks'=>fn($q)=>$q->latest()->limit(1),
            ])
            ->orderBy('product_name')
            ->get()
            ->map(fn($p)=>[
                'id'          => $p->id,
                'product_name'=> $p->product_name,
                'unit_price'  => $p->latest_price,
                'stock'       => $p->current_stock,
            ]);

        return view('orders.create', compact('customers','companies','products'));
    }

    /* -------- POST /orders ---------- */
    public function store(Request $request)
    {
        $data  = $this->validateOrder($request);
        $items = $this->prepareItems($data['items'], $data['order_type']);   // ⭐

        DB::transaction(function () use ($request, $data, $items) {

            $total  = $items->sum(fn($r)=> $r['amount'] * $r['unit_price']);
            $isPaid = $request->boolean('is_paid');

            $order = Order::create([
                'customer_id'   => Auth::user()->customer_id,
                'company_id'    => $data['company_id'] ?? null,
                'order_type'    => $data['order_type'],
                'order_date'    => $data['order_date'],
                'delivery_date' => $data['delivery_date'] ?? null,
                'situation'     => $data['situation'] ?? 'hazırlanıyor',
                'total_amount'  => $total,
                'is_paid'       => $isPaid,
                'paid_at'       => $isPaid ? now() : null,
            ]);

            foreach ($items as $i) {
                $order->products()->attach(
                    $i['product_id'],
                    ['amount'=>$i['amount'],'unit_price'=>$i['unit_price']]
                );

                /* stok hareketi */
                $delta = $data['order_type'] === 'sale'     // ⭐
                       ? -$i['amount']
                       :  $i['amount'];
                $this->moveStock($i['product_id'], $delta); // ⭐
            }
        });

        return redirect()->route('orders.index')
                    ->with('success', 'Sipariş başarıyla eklendi!');
    }

    /* -------- GET /orders/{order}/edit ---------- */
    public function edit(Order $order)
    {
        $order->load('products');

        $customers = Customer::whereKey(Auth::user()->customer_id)->get();
        $companies = Company::where('customer_id', Auth::user()->customer_id)
                            ->orderBy('Company_name')->get();

        $products = Product::where('customer_id', Auth::user()->customer_id)
            ->with([
                'prices'=>fn($q)=>$q->latest()->limit(1),
                'stocks'=>fn($q)=>$q->latest()->limit(1),
            ])
            ->orderBy('product_name')
            ->get()
            ->map(fn($p)=>[
                'id'          => $p->id,
                'product_name'=> $p->product_name,
                'unit_price'  => $p->latest_price,
                'stock'       => $p->current_stock,
            ]);

        return view('orders.edit', compact('order','customers','companies','products'));
    }

    private function availableStock(Product $p, array $oldQtyMap, string $orderType): int
{
    // Alış (purchase) siparişlerinde stok kontrolü yapılmaz
    if ($orderType !== 'sale') {
        return PHP_INT_MAX;
    }

    $current  = $p->current_stock;          // fiilî stok
    $previous = $oldQtyMap[$p->id] ?? 0;    // eski siparişteki miktar
    return $current + $previous;            // “geri konmuş” sanal stok
}
    /* -------- PUT /orders/{order} ---------- */
    public function update(Request $request, Order $order)
{
    // eski satırların miktar haritası  [product_id => eski_adet]
    $oldQtyMap = $order->products
                       ->pluck('pivot.amount', 'id')
                       ->toArray();

    $data  = $this->validateOrder($request, $order);

    // stok denetimi için eski miktar haritasını da gönderiyoruz
    $items = $this->prepareItems(
                 $data['items'],
                 $data['order_type'],
                 $oldQtyMap
             );

    DB::transaction(function () use ($request, $data, $items, $order, $oldQtyMap) {

        /* -------- eski stok etkisini geri sar -------- */
        foreach ($oldQtyMap as $pid => $qty) {
            $delta = $order->order_type === 'sale' ?  $qty : -$qty;
            $this->moveStock($pid, $delta);
        }

        /* -------- siparişi güncelle -------- */
        $total  = $items->sum(fn ($r) => $r['amount'] * $r['unit_price']);
        $isPaid = $request->boolean('is_paid');

        $order->update([
            'company_id'    => $data['company_id'] ?? null,
            'order_type'    => $data['order_type'],
            'order_date'    => $data['order_date'],
            'delivery_date' => $data['delivery_date'] ?? null,
            'situation'     => $data['situation'] ?? $order->situation,
            'total_amount'  => $total,
            'is_paid'       => $isPaid,
            'paid_at'       => $isPaid ? now() : null,
        ]);

        /* -------- pivot & stok hareketleri -------- */
        $order->products()->sync([]);
        foreach ($items as $i) {
            $order->products()->attach(
                $i['product_id'],
                ['amount' => $i['amount'], 'unit_price' => $i['unit_price']]
            );

            $delta = $data['order_type'] === 'sale'
                   ? -$i['amount']        // satış → stok düş
                   :  $i['amount'];       // alış  → stok art
            $this->moveStock($i['product_id'], $delta);
        }
    });

    return redirect()->route('orders.index')
                     ->with('success', 'Sipariş güncellendi!');
}

    /* -------- DELETE /orders/{order} ---------- */
    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            foreach ($order->products as $line) {
                $delta = $order->order_type === 'sale'
                       ?  $line->pivot->amount     // satış iptali → stok geri
                       : -$line->pivot->amount;    // alış iptali  → stok düş
                $this->moveStock($line->id, $delta);                                 // ⭐
            }
            $order->products()->detach();
            $order->delete();
        });

        return redirect()->route('orders.index')
                    ->with('success', 'Sipariş silindi.');
    }

    /* ======== Yardımcılar ======== */

    private function validateOrder(Request $request, ?Order $order=null): array
    {
        return $request->validate([
            'company_id'         => 'nullable|exists:companies,id',
            'order_type'         => 'required|in:sale,purchase',
            'order_date'         => 'required|date',
            'delivery_date'      => 'nullable|date|after_or_equal:order_date',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id|distinct',
            'items.*.amount'     => 'required|numeric|min:1',
            'situation'          => 'in:hazırlanıyor,tamamlandı',
            'is_paid'            => 'sometimes|boolean',
        ]);
    }

    /** stok kontrolü yalnızca satışta yapılır */
   private function prepareItems(
    array  $items,
    string $orderType,
    array  $oldQtyMap = []                 // <- update()’ten gelen harita
) {
    return collect($items)->map(function ($i, $k) use ($orderType, $oldQtyMap) {

        $p = Product::with([
                'prices' => fn ($q) => $q->latest()->limit(1),
                'stocks' => fn ($q) => $q->latest()->limit(1),
            ])->find($i['product_id']);

        /* stok kontrolü yalnızca satışta */
        $available = $this->availableStock($p, $oldQtyMap, $orderType);
        if ($orderType === 'sale' && $i['amount'] > $available) {
            throw ValidationException::withMessages([
                "items.$k.amount" => "Yetersiz stok – mevcut: {$p->current_stock}",
            ]);
        }

        return [
            'product_id' => $p->id,
            'amount'     => $i['amount'],
            'unit_price' => $p->latest_price,
        ];
    });
}

    /** stok hareketi: delta kadar ekle/çıkar (+ artar, - düşer) */
    /** stok hareketi: delta (+ artar, - düşer) */
private function moveStock(int $productId, int $delta): void
{
    if ($delta === 0) {
        return;
    }

    /* son miktarı oku – id’ye göre en yeni kayıt */
    $lastQty = ProductStock::where('product_id', $productId)
                           ->orderByDesc('id')
                           ->value('stock_quantity') ?? 0;

    /* her hareket için YENİ satır yaz (geçmişi koru) */
    ProductStock::create([
        'product_id'     => $productId,
        'stock_quantity' => $lastQty + $delta,
        'update_date'    => now(),
        'updated_by'     => Auth::id(),
    ]);
}
 public function show(Order $order)
{
    if ($order->customer_id !== Auth::user()->customer_id) {
        abort(403, 'Bu siparişe erişim yetkiniz yok.');
    }

    // ‘orderLines’ yerine mevcut ilişki adı ‘orderProducts’ kullanılıyor
    $order->load('customer', 'company', 'orderProducts.product');

    return view('orders.show', compact('order'));
}

}
