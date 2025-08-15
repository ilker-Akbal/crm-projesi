<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Offer;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Company;

class OfferController extends Controller
{
    /* ---------------- LIST (TEKLİFLER) ---------------- */
    public function index()
    {
        $offers = Offer::where('customer_id', Auth::user()->customer_id)
                       ->with(['company'])         // şirket adını göstermek için
                       ->latest('offer_date')
                       ->get();

        return view('offers.index', compact('offers')); // <<< DOĞRU VIEW
    }

    /* ---------------- CREATE FORM ---------------- */
    public function create()
    {
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();
        $companies = Company::where('customer_id', Auth::user()->customer_id)
                            ->orderBy('company_name')
                            ->get();

        // Eğer create sayfasında sipariş bağlama seçeneği varsa kullanırsın
        $orders = Order::where('customer_id', Auth::user()->customer_id)
                       ->latest('order_date')
                       ->get();

        $products = Product::where('customer_id', Auth::user()->customer_id)
            ->with([
                'prices' => fn($q) => $q->latest()->limit(1),
                'stocks' => fn($q) => $q->latest()->limit(1),
            ])
            ->orderBy('product_name')
            ->get()
            ->map(fn($p) => [
                'id'           => $p->id,
                'product_name' => $p->product_name,
                'unit_price'   => $p->latest_price,
                'stock'        => $p->current_stock,
            ]);

        return view('offers.create', compact('customers','companies','orders','products'));
    }

    /* ---------------- STORE ---------------- */
    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id'           => 'required|exists:companies,id', // yeni teklifler için zorunlu
            'offer_date'           => 'required|date',
            'delivery_date'        => 'nullable|date|after_or_equal:offer_date',
            'valid_until'          => 'nullable|date|after_or_equal:offer_date',
            'status'               => 'required|in:hazırlanıyor,gönderildi,kabul,reddedildi',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id|distinct',
            'items.*.amount'       => 'required|numeric|min:1',
        ]);

        $items = collect($data['items'])->map(function ($i, $k) {
            $p = Product::with([
                    'prices' => fn($q)=>$q->latest()->limit(1),
                    'stocks' => fn($q)=>$q->latest()->limit(1),
                 ])->find($i['product_id']);

            $stock = $p->current_stock;
            if ($i['amount'] > $stock) {
                throw ValidationException::withMessages([
                    "items.$k.amount" => "Yetersiz stok – mevcut: $stock",
                ]);
            }

            return [
                'product_id' => $p->id,
                'amount'     => $i['amount'],
                'unit_price' => $p->latest_price,
            ];
        });

        $total = $items->sum(fn($r)=> $r['amount'] * $r['unit_price']);

        // Sadece TEKLİF oluştur (sipariş oluşturma yok)
        $offer = Offer::create([
            'customer_id'   => Auth::user()->customer_id,
            'company_id'    => $data['company_id'] ?? null,
            'offer_date'    => $data['offer_date'],
            'delivery_date' => $data['delivery_date'] ?? null,
            'valid_until'   => $data['valid_until'] ?? null,
            'status'        => $data['status'],
            'total_amount'  => $total,
        ]);

        foreach ($items as $i) {
            $offer->products()->attach(
                $i['product_id'],
                ['amount'=>$i['amount'],'unit_price'=>$i['unit_price']]
            );
        }

        return redirect()->route('offers.index')
                         ->with('success','Teklif başarıyla oluşturuldu.');
    }

    /* ---------------- SHOW ---------------- */
    public function show(Offer $offer)
    {
        $offer->load(['customer','company','order','products']);
        return view('offers.show', compact('offer'));
    }

    /* ---------------- EDIT FORM ---------------- */
    public function edit(Offer $offer)
    {
        $offer->load(['products', 'company']);

        $companies = Company::where('customer_id', Auth::user()->customer_id)
                            ->orderBy('company_name')
                            ->get();

        $products = Product::where('customer_id', Auth::user()->customer_id)
            ->with([
                'prices' => fn($q)=>$q->latest()->limit(1),
                'stocks' => fn($q)=>$q->latest()->limit(1),
            ])
            ->orderBy('product_name')->get()
            ->map(fn($p)=>[
                'id'           => $p->id,
                'product_name' => $p->product_name,
                'unit_price'   => $p->latest_price,
                'stock'        => $p->current_stock,
            ]);

        return view('offers.edit', compact('offer','companies','products'));
    }

    /* ---------------- UPDATE ---------------- */
    public function update(Request $request, Offer $offer)
    {
        $data = $request->validate([
            'company_id'           => 'nullable|exists:companies,id|required_if:status,kabul',
            'offer_date'           => 'required|date',
            'delivery_date'        => 'nullable|date|after_or_equal:offer_date',
            'valid_until'          => 'nullable|date|after_or_equal:offer_date',
            'status'               => 'required|in:hazırlanıyor,gönderildi,kabul,reddedildi',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id|distinct',
            'items.*.amount'       => 'required|numeric|min:1',
        ]);

        $prevStatus = $offer->status;

        $items = collect($data['items'])->map(function ($i,$k){
            $p = Product::with([
                    'prices'=>fn($q)=>$q->latest()->limit(1),
                    'stocks'=>fn($q)=>$q->latest()->limit(1)
                 ])->find($i['product_id']);

            $stock = $p->current_stock;
            if ($i['amount'] > $stock) {
                throw ValidationException::withMessages([
                    "items.$k.amount" => "Yetersiz stok – mevcut: $stock",
                ]);
            }

            return [
                'product_id'=>$p->id,
                'amount'    =>$i['amount'],
                'unit_price'=>$p->latest_price,
            ];
        });

        $total = $items->sum(fn($r)=> $r['amount'] * $r['unit_price']);

        $offer->update([
            'customer_id'   => Auth::user()->customer_id,
            'company_id'    => $data['company_id'] ?? null,
            'offer_date'    => $data['offer_date'],
            'delivery_date' => $data['delivery_date'] ?? null,
            'valid_until'   => $data['valid_until'] ?? null,
            'status'        => $data['status'],
            'total_amount'  => $total,
        ]);

        $offer->products()->sync([]);
        foreach ($items as $i) {
            $offer->products()->attach(
                $i['product_id'],
                ['amount'=>$i['amount'],'unit_price'=>$i['unit_price']]
            );
        }

        // Sadece burada: kabul’e geçişte sipariş oluştur
        if ($prevStatus !== 'kabul' && $offer->status === 'kabul') {
            $this->createOrderFromOffer($offer);
        }

        return redirect()->route('offers.index')
                         ->with('success','Teklif başarıyla güncellendi.');
    }

    /* ---------------- DESTROY ---------------- */
    public function destroy(Offer $offer)
    {
        $offer->products()->detach();
        $offer->delete();

        return redirect()->route('offers.index')
                         ->with('success','Teklif silindi.');
    }

    /* ==========================================================
       =             PRIVATE HELPERS (tek dosyada)              =
       ========================================================== */

    private function createOrderFromOffer(Offer $offer): Order
    {
        if ($offer->order_id) {
            return Order::findOrFail($offer->order_id);
        }

        $order = Order::create([
            'customer_id'   => $offer->customer_id,
            'company_id'    => $offer->company_id,
            'order_type'    => Order::SALE,
            'order_date'    => $offer->offer_date,
            'delivery_date' => $offer->delivery_date,
            'situation'     => 'hazırlanıyor',
            'total_amount'  => $offer->total_amount,
            'is_paid'       => false,
        ]);

        foreach ($offer->products as $p) {
            $order->products()->attach($p->id, [
                'amount'     => $p->pivot->amount,
                'unit_price' => $p->pivot->unit_price,
            ]);
        }

        $offer->update(['order_id' => $order->id]);

        return $order;
    }
}
