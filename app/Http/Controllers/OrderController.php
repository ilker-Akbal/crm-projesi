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
use App\Models\ProductSerial;

class OrderController extends Controller
{
    /* -------- GET /orders ---------- */
    public function index()
    {
        $orders = Order::where('customer_id', Auth::user()->customer_id)
                       ->with(['customer', 'company'])
                       ->latest('order_date')
                       ->get();

        return view('orders.index', compact('orders'));
    }

    /* -------- GET /orders/create ---------- */
    public function create()
    {
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();
        $companies = Company::where('customer_id', Auth::user()->customer_id)
                            ->orderBy('company_name')
                            ->get();

        $products  = Product::where('customer_id', Auth::user()->customer_id)
            ->with([
                'prices' => fn ($q) => $q->latest()->limit(1),
                'stocks' => fn ($q) => $q->latest()->limit(1),
            ])
            ->orderBy('product_name')
            ->get()
            ->map(fn ($p) => [
                'id'           => $p->id,
                'product_name' => $p->product_name,
                'unit_price'   => $p->latest_price,
                'stock'        => (int) ($p->available_stock ?? 0),
            ]);

        return view('orders.create', compact('customers', 'companies', 'products'));
    }

    /* ----------------- REDIRECT HELPER ----------------- */
    protected function redirectAfterSave(Order $order, bool $justPaid = false)
    {
        $isPurchase       = $order->order_type === Order::PURCHASE;
        $shouldEnterSerials = $isPurchase
            && ($justPaid || $order->is_paid && !$justPaid);

        if ($shouldEnterSerials) {
            $first = $order->products()->first();
            return redirect()
                ->route('products.serials.create', [
                    'product' => $first->id,
                    'order'   => $order->id,
                ])
                ->with('success', $justPaid
                    ? 'Ödeme onaylandı – seri numaralarını girin.'
                    : 'Sipariş eklendi – seri numaralarını girin.');
        }

        return redirect()->route('orders.index')
                         ->with('success', $justPaid
                             ? 'Sipariş güncellendi!'
                             : 'Sipariş başarıyla eklendi!');
    }

    /* -------- POST /orders ---------- */
    public function store(Request $request)
    {
        $data   = $this->validateOrder($request);
        $items  = $this->prepareItems($data['items'], $data['order_type']);
        $isPaid = $request->boolean('is_paid');

        $order = DB::transaction(function () use ($data, $items, $isPaid) {
            $order = Order::create([
                'customer_id'   => Auth::user()->customer_id,
                'company_id'    => $data['company_id'] ?? null,
                'order_type'    => $data['order_type'],
                'order_date'    => $data['order_date'],
                'delivery_date' => $data['delivery_date'] ?? null,
                'situation'     => $data['situation'] ?? 'hazırlanıyor',
                'total_amount'  => $items->sum(fn ($r) => $r['amount'] * $r['unit_price']),
                'is_paid'       => $isPaid,
                'paid_at'       => $isPaid ? now() : null,
            ]);

            foreach ($items as $i) {
                $order->products()->attach($i['product_id'], [
                    'amount'     => $i['amount'],
                    'unit_price' => $i['unit_price'],
                ]);

                if ($data['order_type'] === Order::SALE) {
                    $this->reserveStock($i['product_id'], $i['amount']);
                    $this->markSerialsReserved($i['product_id'], $i['amount'], $order->id);

                    if ($isPaid) {
                        $this->finalizeSale($order);
                    }
                }
            }

            return $order;
        });

        return $this->redirectAfterSave($order);
    }

    /* -------- GET /orders/{order}/edit ---------- */
    public function edit(Order $order)
    {
        $order->load('products');

        $customers = Customer::whereKey(Auth::user()->customer_id)->get();
        $companies = Company::where('customer_id', Auth::user()->customer_id)
                            ->orderBy('company_name')
                            ->get();

        $products  = Product::where('customer_id', Auth::user()->customer_id)
            ->with([
                'prices' => fn ($q) => $q->latest()->limit(1),
                'stocks' => fn ($q) => $q->latest()->limit(1),
            ])
            ->orderBy('product_name')
            ->get()
            ->map(fn ($p) => [
                'id'           => $p->id,
                'product_name' => $p->product_name,
                'unit_price'   => $p->latest_price,
                'stock'        => (int) ($p->available_stock ?? 0),
            ]);

        return view('orders.edit', compact('order', 'customers', 'companies', 'products'));
    }

    /* -------- PUT /orders/{order} ---------- */
    public function update(Request $request, Order $order)
    {
        $oldQtyMap = $order->products->pluck('pivot.amount', 'id')->toArray();
        $data      = $this->validateOrder($request, $order);
        $items     = $this->prepareItems($data['items'], $data['order_type'], $oldQtyMap);
        $wasPaid   = $order->is_paid;
        $isPaid    = $request->boolean('is_paid');

        DB::transaction(function () use ($order, $oldQtyMap, $data, $items, $wasPaid, $isPaid) {
            /* 1) Eski rezerv/stok geri al */
            if ($order->order_type === Order::SALE) {
                foreach ($oldQtyMap as $pid => $qty) {
                    $this->freeReservedStock($pid, $qty, $order->id);
                }
            }

            /* 2) Siparişi güncelle */
            $order->update([
                'company_id'    => $data['company_id'] ?? null,
                'order_type'    => $data['order_type'],
                'order_date'    => $data['order_date'],
                'delivery_date' => $data['delivery_date'] ?? null,
                'situation'     => $data['situation'] ?? $order->situation,
                'total_amount'  => collect($items)->sum(fn ($r) => $r['amount'] * $r['unit_price']),
                'is_paid'       => $isPaid,
                'paid_at'       => $isPaid ? now() : null,
            ]);

            /* 3) Pivot satırlarını yeniden ekle */
            $order->products()->sync([]);
            foreach ($items as $i) {
                $order->products()->attach($i['product_id'], [
                    'amount'     => $i['amount'],
                    'unit_price' => $i['unit_price'],
                ]);

                if ($data['order_type'] === Order::SALE) {
                    $this->reserveStock($i['product_id'], $i['amount']);
                    $this->markSerialsReserved($i['product_id'], $i['amount'], $order->id);
                }
            }

            /* 4) Satış + yeni ödeme onayı */
            if (! $wasPaid && $isPaid && $order->order_type === Order::SALE) {
                $this->finalizeSale($order);
            }
        });

        $order->refresh();

        $nowPaidAndPurchase = ! $wasPaid
            && $order->is_paid
            && $order->order_type === Order::PURCHASE;

        return $this->redirectAfterSave($order, $nowPaidAndPurchase);
    }

    /* -------- DELETE /orders/{order} ---------- */
    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            foreach ($order->products as $line) {
                $qty = $line->pivot->amount;

                if ($order->order_type === Order::SALE) {
                    $order->is_paid
                        ? $this->moveStock($line->id, $qty)
                        : $this->freeReservedStock($line->id, $qty, $order->id);
                } else {
                    $this->moveStock($line->id, -$qty);
                }
            }

            $order->products()->detach();
            $order->delete();
        });

        return redirect()->route('orders.index')
                         ->with('success', 'Sipariş silindi.');
    }

    /* -------- GET /orders/{order} ---------- */
    public function show(Order $order)
    {
        abort_if(
            $order->customer_id !== Auth::user()->customer_id,
            403,
            'Bu siparişe erişim yetkiniz yok.'
        );

        $order->load('customer', 'company', 'orderProducts.product');

        return view('orders.show', compact('order'));
    }

    /* ===================== HELPER METOTLAR ===================== */

    private function validateOrder(Request $request, ?Order $order = null): array
    {
        return $request->validate([
            'company_id'           => 'nullable|exists:companies,id', // ⭐
            'order_date'           => 'required|date',
            'delivery_date'        => 'required|date|after_or_equal:order_date',
            'order_type'           => 'required|in:' . Order::SALE . ',' . Order::PURCHASE,
            'situation'            => 'nullable|string',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.amount'       => 'required|integer|min:1',
            'items.*.unit_price'   => 'required|numeric|min:0',
        ]);
    }

    /** Satışta stok limitini kontrol ederek kalem listesi hazırlar. */
    private function prepareItems(
        array  $items,
        string $orderType,
        array  $oldQtyMap = []
    ) {
        return collect($items)->map(function ($i, $k) use ($orderType, $oldQtyMap) {
            $p = Product::with([
                    'prices' => fn ($q) => $q->latest()->limit(1),
                    'stocks' => fn ($q) => $q->latest()->limit(1),
                ])->find($i['product_id']);

            $available = $this->availableStock($p, $oldQtyMap, $orderType);
            if ($orderType === Order::SALE && $i['amount'] > $available) {
                throw ValidationException::withMessages([
                    "items.$k.amount" => "Yetersiz stok – mevcut: {$available}",
                ]);
            }

            return [
                'product_id' => $p->id,
                'amount'     => $i['amount'],
                'unit_price' => $p->latest_price,
            ];
        });
    }

    /** Satışta stok kontrolü; alışta limitsiz. */
    private function availableStock(Product $p, array $oldQtyMap, string $orderType): int
    {
        if ($orderType !== Order::SALE) {
            return PHP_INT_MAX;
        }

        $current  = $p->available_stock;
        $previous = $oldQtyMap[$p->id] ?? 0;
        return $current + $previous;
    }

    /** Stok hareketi: delta (+ artar, - düşer) */
    public function moveStock(int $productId, int $delta): void
    {
        if ($delta === 0) return;

        $last = ProductStock::where('product_id', $productId)
                   ->orderByDesc('id')
                   ->first();

        $prevQty      = $last?->stock_quantity  ?? 0;
        $prevBlocked  = $last?->blocked_stock   ?? 0;
        $prevReserved = $last?->reserved_stock  ?? 0;

        ProductStock::create([
            'product_id'     => $productId,
            'stock_quantity' => $prevQty + $delta,
            'blocked_stock'  => $prevBlocked,
            'reserved_stock' => $prevReserved,
            'update_date'    => now(),
            'updated_by'     => Auth::id(),
        ]);
    }

    /** Satış rezervasyonu */
    private function reserveStock(int $productId, int $qty): void
    {
        if ($qty <= 0) return;

        $stock = ProductStock::latest('id')->firstWhere('product_id', $productId);
        throw_if(
            ! $stock || $stock->available_stock < $qty,
            ValidationException::withMessages(['stok' => 'Yetersiz stok (rezerv yapılamadı)'])
        );

        $stock->increment('reserved_stock', $qty);
    }

    /** Satış ödeme onayı → stok düşümü */
    private function finalizeSale(Order $order): void
    {
        foreach ($order->products as $p) {
            $qty   = $p->pivot->amount;
            $stock = ProductStock::latest('id')->firstWhere('product_id', $p->id);

            if (! $stock) continue;

            $stock->decrement('reserved_stock', $qty);
            $stock->decrement('stock_quantity', $qty);

            ProductSerial::where('order_id', $order->id)
                         ->where('product_id', $p->id)
                         ->update(['status' => ProductSerial::SOLD]);
        }
    }

    /** Rezerv iptali (satış silme/güncelleme) */
    private function freeReservedStock(int $productId, int $qty, int $orderId): void
    {
        if ($qty <= 0) return;

        $stock = ProductStock::latest('id')->firstWhere('product_id', $productId);
        if ($stock) {
            $stock->decrement('reserved_stock', $qty);
        }

        $this->unreserveSerials($productId, $qty, $orderId);
    }

    /* ---- Seri numarası yardımcıları ---- */
    private function markSerialsReserved(int $productId, int $qty, int $orderId): void
    {
        ProductSerial::where('product_id', $productId)
            ->where('status', ProductSerial::AVAILABLE)
            ->limit($qty)
            ->update([
                'status'   => ProductSerial::RESERVED,
                'order_id' => $orderId,
            ]);
    }

    private function unreserveSerials(int $productId, int $qty, int $orderId): void
    {
        ProductSerial::where('product_id', $productId)
            ->where('status', ProductSerial::RESERVED)
            ->where('order_id', $orderId)
            ->limit($qty)
            ->update([
                'status'   => ProductSerial::AVAILABLE,
                'order_id' => null,
            ]);
    }
}
