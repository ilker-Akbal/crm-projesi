<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Offer;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;

class OfferController extends Controller
{
    /* -------------------------------------------------
     |  GET /offers  →  Liste
     * ------------------------------------------------*/
    public function index()
    {
        $offers = Offer::where('customer_id', Auth::user()->customer_id)
                       ->with('customer')
                       ->orderBy('offer_date', 'desc')
                       ->get();

        return view('offers.index', compact('offers'));
    }

    /* -------------------------------------------------
     |  GET /offers/create  →  Form
     * ------------------------------------------------*/
    public function create()
    {
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();

        $orders = Order::where('customer_id', Auth::user()->customer_id)
                       ->orderBy('order_date', 'desc')
                       ->get();

        return view('offers.create', compact('customers', 'orders'));
    }

    /* -------------------------------------------------
     |  POST /offers  →  Kaydet
     * ------------------------------------------------*/
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id'              => 'nullable|exists:orders,id',
            'offer_date'            => 'required|date',
            'valid_until'           => 'nullable|date|after_or_equal:offer_date',
            'status'                => 'required|in:hazırlanıyor,gönderildi,kabul,reddedildi',
            'total_amount'          => 'nullable|numeric|min:0',

            /* satır(lar) */
            'items'                 => 'nullable|array',
            'items.*.product_id'    => 'required_with:items.*|exists:products,id',
            'items.*.amount'        => 'required_with:items.*|numeric|min:1',
            'items.*.unit_price'    => 'required_with:items.*|numeric|min:0',
        ]);

        /* toplam hesapla */
        $total = 0;
        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $total += $item['amount'] * $item['unit_price'];
            }
        } else {
            $total = $data['total_amount'] ?? 0;
        }

        $offer = Offer::create([
            'customer_id'  => Auth::user()->customer_id,
            'order_id'     => $data['order_id'] ?? null,
            'offer_date'   => $data['offer_date'],
            'valid_until'  => $data['valid_until'] ?? null,
            'status'       => $data['status'],
            'total_amount' => $total,
        ]);

        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $offer->products()->attach(
                    $item['product_id'],
                    ['amount' => $item['amount'], 'unit_price' => $item['unit_price']]
                );
            }
        }

        return redirect()->route('offers.index')
                         ->with('success', 'Offer created successfully.');
    }

    /* -------------------------------------------------
     |  GET /offers/{offer}  →  Detay
     * ------------------------------------------------*/
    public function show(Offer $offer)
    {
        $offer->load('customer', 'order', 'products');

        return view('offers.show', compact('offer'));
    }

    /* -------------------------------------------------
     |  GET /offers/{offer}/edit  →  Form
     * ------------------------------------------------*/
    public function edit(Offer $offer)
    {
        $customers = Customer::whereKey(Auth::user()->customer_id)->get();

        $orders = Order::where('customer_id', Auth::user()->customer_id)
                       ->orderBy('order_date', 'desc')
                       ->get();

        $offer->load('products');

        return view('offers.edit', compact('offer', 'customers', 'orders'));
    }

    /* -------------------------------------------------
     |  PUT /offers/{offer}  →  Güncelle
     * ------------------------------------------------*/
    public function update(Request $request, Offer $offer)
    {
        $data = $request->validate([
            'order_id'              => 'nullable|exists:orders,id',
            'offer_date'            => 'required|date',
            'valid_until'           => 'nullable|date|after_or_equal:offer_date',
            'status'                => 'required|in:hazırlanıyor,gönderildi,kabul,reddedildi',
            'total_amount'          => 'nullable|numeric|min:0',

            /* satır(lar) */
            'items'                 => 'nullable|array',
            'items.*.product_id'    => 'required_with:items.*|exists:products,id',
            'items.*.amount'        => 'required_with:items.*|numeric|min:1',
            'items.*.unit_price'    => 'required_with:items.*|numeric|min:0',
        ]);

        $total = 0;
        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $total += $item['amount'] * $item['unit_price'];
            }
        } else {
            $total = $data['total_amount'] ?? 0;
        }

        $offer->update([
            'customer_id'  => Auth::user()->customer_id,
            'order_id'     => $data['order_id'] ?? null,
            'offer_date'   => $data['offer_date'],
            'valid_until'  => $data['valid_until'] ?? null,
            'status'       => $data['status'],
            'total_amount' => $total,
        ]);

        /* pivot tabloyu yenile */
        $offer->products()->sync([]);
        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $offer->products()->attach(
                    $item['product_id'],
                    ['amount' => $item['amount'], 'unit_price' => $item['unit_price']]
                );
            }
        }

        return redirect()->route('offers.index')
                         ->with('success', 'Offer updated successfully.');
    }

    /* -------------------------------------------------
     |  DELETE /offers/{offer}  →  Sil
     * ------------------------------------------------*/
    public function destroy(Offer $offer)
    {
        $offer->products()->detach();
        $offer->delete();

        return redirect()->route('offers.index')
                         ->with('success', 'Offer deleted successfully.');
    }
}
