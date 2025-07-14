<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Customer;
use App\Models\Order;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::with('customer')->orderBy('offer_date','desc')->get();
        return view('offers.index',compact('offers'));
    }

    public function create()
    {
        $customers = Customer::orderBy('customer_name')->get();
        $orders    = Order::orderBy('Order_Date','desc')->get();
        return view('offers.create',compact('customers','orders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_id'    => 'nullable|exists:orders,id',
            'offer_date'  => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:offer_date',
            'status'      => 'required|in:hazırlanıyor,gönderildi,kabul,reddedildi',
            'total_amount'=> 'nullable|numeric|min:0',
        ]);

        Offer::create($data);

        return redirect()->route('offers.index')
            ->with('success','Offer created successfully.');
    }

    public function show(Offer $offer)
    {
        $offer->load('customer','order');
        return view('offers.show',compact('offer'));
    }

    public function edit(Offer $offer)
    {
        $customers = Customer::orderBy('customer_name')->get();
        $orders    = Order::orderBy('Order_Date','desc')->get();
        return view('offers.edit',compact('offer','customers','orders'));
    }

    public function update(Request $request, Offer $offer)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_id'    => 'nullable|exists:orders,id',
            'offer_date'  => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:offer_date',
            'status'      => 'required|in:hazırlanıyor,gönderildi,kabul,reddedildi',
            'total_amount'=> 'nullable|numeric|min:0',
        ]);

        $offer->update($data);

        return redirect()->route('offers.index')
            ->with('success','Offer updated successfully.');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('offers.index')
            ->with('success','Offer deleted successfully.');
    }
}
