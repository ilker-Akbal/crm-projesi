<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Offer;     // İleride gerçek veride açarsınız
// use App\Models\Customer;
// use App\Models\Order;
// use App\Models\Product;

class OfferController extends Controller
{
    /** GET /offers  – liste */
    public function index()
    {
        // $offers = Offer::with('customer')->get();
        $offers = collect();           // şimdilik boş
        return view('offers.index', compact('offers'));
    }

    /** GET /offers/create – form */
    public function create()
    {
        // $customers = Customer::orderBy('customer_name')->get();
        // $orders    = Order::select('id')->get();
        // $products  = Product::with('price')->get();
        $customers = collect();        // şimdilik boş
        $orders    = collect();
        $products  = collect();

        return view('offers.create', compact('customers', 'orders', 'products'));
    }

    /** POST /offers – şimdilik kayıt yapmıyoruz */
    public function store(Request $request)
    {
        return back()->with('success', 'Demo aşamasında veri kaydedilmiyor.');
    }

    /* Diğer metodlar ileride doldurulabilir */
    public function show($id)    {}
    public function edit($id)    {}
    public function update(Request $r, $id) {}
    public function destroy($id) {}
}
