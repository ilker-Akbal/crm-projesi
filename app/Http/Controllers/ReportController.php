<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\ProductStock;
use App\Models\CurrentCard;
use App\Models\SupportRequest;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /* ===  Satış Raporu  === */
    public function sales()
    {
        // orders tablosu customer ilişkisiyle
        $sales = Order::with('customer')
                      ->latest('order_date')
                      ->get();

        return view('reports.sales', compact('sales'));
    }

    /* ===  Müşteri Raporu  === */
    public function customers()
    {
        $customers = Customer::all();
        return view('reports.customers', compact('customers'));
    }

    /* ===  Ürün Stok Raporu  === */
    public function productStock()
    {
        $stocks = ProductStock::with('product')->get();
        return view('reports.product_stock', compact('stocks'));
    }

    /* ===  Cari Hesap Özeti  === */
    public function currentAccountSummary()
    {
        $accounts = CurrentCard::with('customer')->get();
        return view('reports.current_account', compact('accounts'));
    }

    /* ===  Destek Talep Raporu  === */
    public function supportRequest()
    {
        $requests = SupportRequest::with('customer')->latest()->get();
        return view('reports.support_request', compact('requests'));
    }

    /*
     |--------------------------------------------------------------
     |  Resource route’tan gelen diğer metotlar (şimdilik boş)
     |--------------------------------------------------------------
     */
    public function index()   {}
    public function create()  {}
    public function store(Request $r) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $r,$id){}
    public function destroy($id){}
}
