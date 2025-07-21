<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{
    Customer, Offer, Order, Reminder,
    SupportRequest, Product, OrderProduct
};

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user        = auth()->user();
        $customerId  = $user->customer_id;   // giriş yapan kullanıcının firması

        /* ---------------------- METRİKLER ---------------------- */
        $metrics = [
            // İsterseniz 'customers' metriğini kaldırabilirsiniz (zaten 1 olur)
            'customers'      => 1,
            'openOffers'     => Offer::where('customer_id', $customerId)
                                      ->where('status', 'hazırlanıyor')
                                      ->count(),
            'openOrders'     => Order::where('customer_id', $customerId)
                                      ->where('situation', 'hazırlanıyor')
                                      ->count(),
            'todayReminders' => Reminder::where('customer_id', $customerId)
                                        ->whereDate('reminder_date', today())
                                        ->count(),
            'openSupports'   => SupportRequest::where('customer_id', $customerId)
                                              ->where('situation', 'açık')
                                              ->count(),
        ];

        /* --------------------- AYLIK CİRO ---------------------- */
        $revenue = Order::where('customer_id', $customerId)
                        ->selectRaw("
                            DATE_FORMAT(order_date, '%Y-%m') AS ym,
                            SUM(total_amount)                AS total
                        ")
                        ->where('order_date', '>=', now()->subMonths(11)->startOfMonth())
                        ->groupBy('ym')
                        ->orderBy('ym')
                        ->pluck('total', 'ym');

        /* ------------------ EN ÇOK SATILAN 5 ------------------- */
        $topProducts = OrderProduct::whereHas(
                            'order',
                            fn($q) => $q->where('customer_id', $customerId)
                        )
                        ->selectRaw('product_id, SUM(amount) AS qty')
                        ->groupBy('product_id')
                        ->orderByDesc('qty')
                        ->with('product')
                        ->limit(5)
                        ->get();

        /* --------------- YAKLAŞAN TESLİMLER -------------------- */
        $upcoming = Order::where('customer_id', $customerId)
                         ->whereBetween('delivery_date', [today(), today()->addDays(10)])
                         ->orderBy('delivery_date')
                         ->with('customer')
                         ->get();

        /* --------------- DÜŞÜK STOK ÜRÜNLER -------------------- */
        $lowStock = Product::where('customer_id', $customerId)
                           ->with('stocks')
                           ->get()
                           ->filter(fn($p) => optional($p->stocks->last())->stock_quantity < 20);

        return view('dashboard.index', compact(
            'metrics',
            'revenue',
            'topProducts',
            'upcoming',
            'lowStock'
        ));
    }
}
