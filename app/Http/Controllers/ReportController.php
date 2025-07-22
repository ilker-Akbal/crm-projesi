<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Customer;
use App\Models\ProductStock;
use App\Models\CurrentCard;
use App\Models\SupportRequest;

class ReportController extends Controller
{
    /* -------------------------------------------------
     |  Satış Raporu
     * ------------------------------------------------*/
    public function sales()
    {
        $sales = Order::where('customer_id', Auth::user()->customer_id)
                      ->with('customer')
                      ->orderByDesc('order_date')
                      ->get();

        return view('reports.sales', compact('sales'));
    }

    /* -------------------------------------------------
     |  Müşteri Raporu
     * ------------------------------------------------*/
    public function customers()
    {
        // Kullanıcı tek müşteriye bağlıysa yalnızca onu getiriyoruz.
        $customers = Customer::whereKey(Auth::user()->customer_id)
                             ->orderBy('customer_name')
                             ->get();

        return view('reports.customers', compact('customers'));
    }

    /* -------------------------------------------------
     |  Ürün Stok Raporu
     * ------------------------------------------------*/
    public function productStock()
    {
        $stocks = ProductStock::whereHas(
                        'product',
                        fn ($q) => $q->where('customer_id', Auth::user()->customer_id)
                    )
                    ->with('product')
                    ->latest('update_date')
                    ->get();

        return view('reports.product_stock', compact('stocks'));
    }

    /* -------------------------------------------------
     |  Cari Hesap Özeti
     * ------------------------------------------------*/
    public function currentAccountSummary()
    {
        $accounts = CurrentCard::where('customer_id', Auth::user()->customer_id)
                               ->with('customer')
                               ->orderByDesc('opening_date')
                               ->get();

        return view('reports.current_account', compact('accounts'));
    }

    /* -------------------------------------------------
     |  Destek Talep Raporu
     * ------------------------------------------------*/
    public function supportRequest()
    {
        $requests = SupportRequest::where('customer_id', Auth::user()->customer_id)
                                  ->with('customer')
                                  ->orderByDesc('registration_date')
                                  ->get();

        return view('reports.support_request', compact('requests'));
    }

    /* -------- Resource boş kalıpları (şimdilik kullanılmıyor) -------- */
    public function index()   {}
    public function create()  {}
    public function store(Request $r) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $r, $id) {}
    public function destroy($id) {}
}
