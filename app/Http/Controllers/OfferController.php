<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                       ->with(['customer','company'])          // ← şirketi de çek
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
                            ->orderBy('Company_name')->get();           // ←
        $orders    = Order::where('customer_id', Auth::user()->customer_id)
                          ->latest('order_date')->get();

        /* ürünler – en güncel fiyatıyla */
        $products  = Product::where('customer_id', Auth::user()->customer_id)
                            ->with(['prices'=>fn($q)=>$q->latest()->limit(1)])
                            ->orderBy('product_name')->get()
                            ->map(fn($p)=>[
                                'id'           => $p->id,
                                'product_name' => $p->product_name,
                                'unit_price'   => optional($p->prices->first())->price ?? 0,
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
            'company_id'            => 'nullable|exists:companies,id',        // ←
            'order_id'              => 'nullable|exists:orders,id',
            'offer_date'            => 'required|date',
            'valid_until'           => 'nullable|date|after_or_equal:offer_date',
            'status'                => 'required|in:hazırlanıyor,gönderildi,kabul,reddedildi',
            'total_amount'          => 'nullable|numeric|min:0',

            'items'                 => 'nullable|array',
            'items.*.product_id'    => 'required_with:items.*|exists:products,id',
            'items.*.amount'        => 'required_with:items.*|numeric|min:1',
            'items.*.unit_price'    => 'required_with:items.*|numeric|min:0',
        ]);

        /* toplam hesapla */
        $total = collect($data['items'] ?? [])
                   ->reduce(fn($s,$i)=> $s + $i['amount'] * $i['unit_price'], 0)
                 ?: ($data['total_amount'] ?? 0);

        $offer = Offer::create([
            'customer_id'  => Auth::user()->customer_id,
            'company_id'   => $data['company_id'] ?? null,                   // ←
            'order_id'     => $data['order_id'] ?? null,
            'offer_date'   => $data['offer_date'],
            'valid_until'  => $data['valid_until'] ?? null,
            'status'       => $data['status'],
            'total_amount' => $total,
        ]);

        /* kalemler */
        foreach ($data['items'] ?? [] as $item) {
            $offer->products()->attach(
                $item['product_id'],
                ['amount'=>$item['amount'],'unit_price'=>$item['unit_price']]
            );
        }

        return redirect()->route('offers.index')
                         ->with('success','Offer created successfully.');
    }

    /* -------------------------------------------------
     |  GET /offers/{offer}  →  Detay
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
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();
        $companies = Company::where('customer_id', Auth::user()->customer_id)
                            ->orderBy('Company_name')->get();           // ←
        $orders    = Order::where('customer_id', Auth::user()->customer_id)
                          ->latest('order_date')->get();

        $products  = Product::where('customer_id', Auth::user()->customer_id)
                            ->with(['prices'=>fn($q)=>$q->latest()->limit(1)])
                            ->orderBy('product_name')->get()
                            ->map(fn($p)=>[
                                'id'=>$p->id,
                                'product_name'=>$p->product_name,
                                'unit_price'=>optional($p->prices->first())->price ?? 0,
                            ]);

        $offer->load('products');

        return view('offers.edit',
                    compact('offer','customers','companies','orders','products'));
    }

    /* -------------------------------------------------
     |  PUT /offers/{offer}  →  Güncelle
     * ------------------------------------------------*/
    public function update(Request $request, Offer $offer)
    {
        $data = $request->validate([
            'company_id'            => 'nullable|exists:companies,id',        // ←
            'order_id'              => 'nullable|exists:orders,id',
            'offer_date'            => 'required|date',
            'valid_until'           => 'nullable|date|after_or_equal:offer_date',
            'status'                => 'required|in:hazırlanıyor,gönderildi,kabul,reddedildi',
            'total_amount'          => 'nullable|numeric|min:0',

            'items'                 => 'nullable|array',
            'items.*.product_id'    => 'required_with:items.*|exists:products,id',
            'items.*.amount'        => 'required_with:items.*|numeric|min:1',
            'items.*.unit_price'    => 'required_with:items.*|numeric|min:0',
        ]);

        $total = collect($data['items'] ?? [])
                   ->reduce(fn($s,$i)=> $s + $i['amount'] * $i['unit_price'], 0)
                 ?: ($data['total_amount'] ?? 0);

        $offer->update([
            'customer_id'  => Auth::user()->customer_id,
            'company_id'   => $data['company_id'] ?? null,                   // ←
            'order_id'     => $data['order_id'] ?? null,
            'offer_date'   => $data['offer_date'],
            'valid_until'  => $data['valid_until'] ?? null,
            'status'       => $data['status'],
            'total_amount' => $total,
        ]);

        /* pivot yenile */
        $offer->products()->sync([]);
        foreach ($data['items'] ?? [] as $item) {
            $offer->products()->attach(
                $item['product_id'],
                ['amount'=>$item['amount'],'unit_price'=>$item['unit_price']]
            );
        }

        return redirect()->route('offers.index')
                         ->with('success','Offer updated successfully.');
    }

    /* -------------------------------------------------
     |  DELETE /offers/{offer}  →  Sil
     * ------------------------------------------------*/
    public function destroy(Offer $offer)
    {
        $offer->products()->detach();
        $offer->delete();

        return redirect()->route('offers.index')
                         ->with('success','Offer deleted successfully.');
    }
}
