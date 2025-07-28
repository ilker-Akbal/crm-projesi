<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\ProductStock;
use App\Models\Customer;
use App\Models\CurrentCard;
use App\Models\SupportRequest;

class ReportController extends Controller
{
    /* -------------------------------------------------
     |  Satış Raporu – Sol: Toplam Ciro, Sağ: Ürün Adet
     * ------------------------------------------------*/
    public function sales(Request $request)
    {
        $customerId = Auth::user()->customer_id;

        // 1) Tablo için ham siparişler (Ürünleri de eager-load)
        $orders = Order::with(['company', 'products'])
                       ->where('customer_id', $customerId)
                       ->orderByDesc('order_date')
                       ->get();

        // 2) Donut: Şirkete göre Toplam Ciro
        $revenueData = Order::selectRaw(
                            'companies.company_name AS company,
                             SUM(orders.total_amount)       AS revenue'
                        )
                        ->join('companies', 'orders.company_id', '=', 'companies.id')
                        ->where('orders.customer_id', $customerId)
                        ->groupBy('companies.company_name')
                        ->orderByDesc('revenue')
                        ->get();

        // 3) Bar: Şirkete göre Ürün Adet Toplamları
        $rawQty = Order::join('companies',       'orders.company_id',       '=', 'companies.id')
                       ->join('order_products', 'orders.id',               '=', 'order_products.order_id')
                       ->join('products',       'order_products.product_id','=', 'products.id')
                       ->selectRaw(
                           'companies.company_name      AS company,
                            products.product_name       AS product,
                            SUM(order_products.amount)  AS total_qty'
                       )
                       ->where('orders.customer_id', $customerId)
                       ->groupBy('companies.company_name', 'products.product_name')
                       ->get();

        $companies   = $rawQty->pluck('company')->unique()->values();
        $products    = $rawQty->pluck('product')->unique()->values();
        $qtyDatasets = $products->map(function($product, $idx) use ($rawQty, $companies) {
            return [
                'label'           => $product,
                'data'            => $companies->map(function($company) use ($rawQty, $product) {
                    $row = $rawQty->first(fn($r) =>
                        $r->company === $company && $r->product === $product
                    );
                    return $row->total_qty ?? 0;
                }),
                'backgroundColor' => "hsl(" . (($idx * 57) % 360) . ",70%,60%)",
                'stack'           => 'stack1',
            ];
        });

        return view('reports.sales', compact(
            'orders',
            'revenueData',
            'companies',
            'qtyDatasets'
        ));
    }

    /* -------------------------------------------------
     |  Ürün Stok Raporu – mevcut stoğu satışlardan düş
     * ------------------------------------------------*/
    public function productStock()
    {
        $customerId = Auth::user()->customer_id;

        // 1) Son kayıtlı stok miktarları (ürün bazlı en güncel)
        $latestStocks = ProductStock::whereHas('product', function($q) use ($customerId) {
                                $q->where('customer_id', $customerId);
                            })
                            ->with('product')
                            ->orderByDesc('update_date')
                            ->get()
                            ->unique('product_id')
                            ->values();

        // 2) Hangi üründen kaç adet satıldı? (alias: sold_qty)
        $sold = DB::table('order_products')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->where('orders.customer_id', $customerId)
            ->where('orders.order_type', Order::SALE)
            ->select('order_products.product_id', DB::raw('SUM(order_products.amount) AS sold_qty'))
            ->groupBy('order_products.product_id')
            ->pluck('sold_qty', 'product_id');  // [ product_id => sold_qty, … ]

        // 3) Her stok kaydına 'available' alanını ekle
        foreach ($latestStocks as $stock) {
            $soldQty = $sold[$stock->product_id] ?? 0;
            $stock->available = max(0, $stock->stock_quantity - $soldQty);
        }

        return view('reports.product_stock', [
            'stocks' => $latestStocks,
        ]);
    }

    /* -------------------------------------------------
     |  Müşteri Raporu
     * ------------------------------------------------*/
    public function customers()
    {
        $customers = Customer::whereKey(Auth::user()->customer_id)
                             ->orderBy('customer_name')
                             ->get();
        return view('reports.customers', compact('customers'));
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

    /* --------- CRUD iskeletleri (boş gövdeli) --------- */
    public function index()                 {}
    public function create()                {}
    public function store(Request $r)       {}
    public function show($id)               {}
    public function edit($id)               {}
    public function update(Request $r, $id) {}
    public function destroy($id)            {}
}
