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
        /* Basit sayaçlar */
        $metrics = [
            'customers'        => Customer::count(),
            'openOffers'       => Offer::where('status', 'hazırlanıyor')->count(),
            'openOrders'       => Order::where('situation', 'hazırlanıyor')->count(),
            'todayReminders'   => Reminder::whereDate('reminder_date', today())->count(),
            'openSupports'     => SupportRequest::where('situation', 'açık')->count(),
        ];

        /* Aylık ciro (son 12 ay) */
        /* Aylık ciro (son 12 ay) */
$revenue = Order::selectRaw(
                "DATE_FORMAT(order_date, '%Y-%m')  AS ym,
                 SUM(total_amount)                 AS total"
            )
            ->where('order_date', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');

        /* Top 5 ürün (adet) */
        $topProducts = OrderProduct::selectRaw('product_id, sum(amount) as qty')
                                   ->groupBy('product_id')
                                   ->orderByDesc('qty')
                                   ->with('product')           // ilişkili ürün adı için
                                   ->limit(5)->get();

        /* Yaklaşan teslimler (önümüzdeki 10 gün) */
        $upcoming = Order::whereBetween('delivery_date', [today(), today()->addDays(10)])
                         ->orderBy('delivery_date')
                         ->with('customer')
                         ->get();

        /* Düşük stok */
        $lowStock = Product::with('stocks')
                           ->get()
                           ->filter(fn($p) => optional($p->stocks->last())->stock_quantity < 20);

        return view('dashboard.index', compact(
        'metrics',   // ← bu satır varsa Blade değişkeni görür
        'revenue',
        'topProducts',
        'upcoming',
        'lowStock'
    ));
    }
}
