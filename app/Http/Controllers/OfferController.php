<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;     // ⭐ stok hatası için
use App\Models\Offer;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Company;

class OfferController extends Controller
{
    /* -------------------------------------------------
     |  GET /offers  →  Liste
     * ------------------------------------------------*/
    public function index()
    {
        $offers = Offer::where('customer_id', Auth::user()->customer_id)
                       ->with(['customer','company'])
                       ->latest('offer_date')
                       ->get();

        return view('offers.index', compact('offers'));
    }

    /* -------------------------------------------------
     |  GET /offers/create  →  Form
     * ------------------------------------------------*/
    public function create()
    {
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();
        $companies = Company::where('customer_id', Auth::user()->customer_id) 
                            ->orderBy('Company_name')->get();
        $orders    = Order::where('customer_id', Auth::user()->customer_id)
                          ->latest('order_date')->get();

        /* ürünler: en güncel fiyat + stok */
        $products  = Product::where('customer_id', Auth::user()->customer_id)
            ->with([
                'prices'=>fn($q)=>$q->latest()->limit(1),
                'stocks'=>fn($q)=>$q->latest()->limit(1),
            ])
            ->orderBy('product_name')->get()
            ->map(fn($p)=>[
                'id'          => $p->id,
                'product_name'=> $p->product_name,
                'unit_price'  => $p->latest_price,         // ⭐ accessor
                'stock'       => $p->current_stock,        // ⭐ accessor
            ]);

        return view('offers.create',
                    compact('customers','companies','orders','products'));
    }

    /* -------------------------------------------------
     |  POST /offers  →  Kaydet
     * ------------------------------------------------*/
    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id'           => 'nullable|exists:companies,id',
            'order_id'             => 'nullable|exists:orders,id',
            'offer_date'           => 'required|date',
            'valid_until'          => 'nullable|date|after_or_equal:offer_date',
            'status'               => 'required|in:hazırlanıyor,gönderildi,kabul,reddedildi',
            'items'                => 'required|array|min:1',               // ⭐ zorunlu
            'items.*.product_id'   => 'required|exists:products,id|distinct',// ⭐ kopya yok
            'items.*.amount'       => 'required|numeric|min:1',
        ]);

        /* -- stok & fiyat kontrolü -- */
        $items = collect($data['items'])->map(function ($i, $k) {
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
                'product_id' => $p->id,
                'amount'     => $i['amount'],
                'unit_price' => $p->latest_price,          // ⭐ kullanıcıdan gelmiyor
            ];
        });

        $total = $items->sum(fn($r)=> $r['amount'] * $r['unit_price']);

        $offer = Offer::create([
            'customer_id'  =>  Auth::user()->customer_id,
            'company_id'   => $data['company_id'] ?? null,
            'order_id'     => $data['order_id']   ?? null,
            'offer_date'   => $data['offer_date'],
            'valid_until'  => $data['valid_until'] ?? null,
            'status'       => $data['status'],
            'total_amount' => $total,
        ]);

        foreach ($items as $i) {                                          // ⭐
            $offer->products()->attach(
                $i['product_id'],
                ['amount'=>$i['amount'],'unit_price'=>$i['unit_price']]
            );
        }

        return redirect()->route('offers.index')
                         ->with('success','Offer created successfully.');
    }

    /* -------------------------------------------------
     |  GET /offers/{offer} → Detay
     * ------------------------------------------------*/
    public function show(Offer $offer)
    {
        $offer->load(['customer','company','order','products']);
        return view('offers.show', compact('offer'));
    }

    /* -------------------------------------------------
     |  GET /offers/{offer}/edit  →  Form
     * ------------------------------------------------*/
    public function edit(Offer $offer)
    {
        $offer->load('products');

        $customers = Customer::whereKey(Auth::user()->customer_id);
        $companies = Company::where('customer_id', Auth::user()->customer_id)
                            ->orderBy('Company_name')->get();
       $orders = Order::where('customer_id', Auth::user()->customer_id)
                          ->latest('order_date')->get();

        $products = Product::where('customer_id', Auth::user()->customer_id)
            ->with([
                'prices'=>fn($q)=>$q->latest()->limit(1),
                'stocks'=>fn($q)=>$q->latest()->limit(1),
            ])
            ->orderBy('product_name')->get()
            ->map(fn($p)=>[
                'id'          => $p->id,
                'product_name'=> $p->product_name,
                'unit_price'  => $p->latest_price,
                'stock'       => $p->current_stock,
            ]);

        return view('offers.edit',
                    compact('offer','customers','companies','orders','products'));
    }

    /* -------------------------------------------------
     |  PUT /offers/{offer}  →  Güncelle
     * ------------------------------------------------*/
    public function update(Request $request, Offer $offer)
    {
        $data = $request->validate([
            'company_id'           => 'nullable|exists:companies,id',
            'order_id'             => 'nullable|exists:orders,id',
            'offer_date'           => 'required|date',
            'valid_until'          => 'nullable|date|after_or_equal:offer_date',
            'status'               => 'required|in:hazırlanıyor,gönderildi,kabul,reddedildi',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id|distinct',
            'items.*.amount'       => 'required|numeric|min:1',
        ]);

        /* stok + fiyat */
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
            'customer_id'  =>  Auth::user()->customer_id,
            'company_id'   => $data['company_id'] ?? null,
            'order_id'     => $data['order_id']   ?? null,
            'offer_date'   => $data['offer_date'],
            'valid_until'  => $data['valid_until'] ?? null,
            'status'       => $data['status'],
            'total_amount' => $total,
        ]);

        /* pivot yenile */
        $offer->products()->sync([]);
        foreach ($items as $i) {
            $offer->products()->attach(
                $i['product_id'],
                ['amount'=>$i['amount'],'unit_price'=>$i['unit_price']]
            );
        }

        return redirect()->route('offers.index')
                         ->with('success','Offer updated successfully.');
    }

    /* -------------------------------------------------
     |  DELETE /offers/{offer}
     * ------------------------------------------------*/
    public function destroy(Offer $offer)
    {
        $offer->products()->detach();
        $offer->delete();

        return redirect()->route('offers.index')
                         ->with('success','Offer deleted successfully.');
    }
}
