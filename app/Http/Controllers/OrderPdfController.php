<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Order;

class OrderPdfController extends Controller
{
    public function exportPdf()
    {
        $orders = Order::where('customer_id', Auth::user()->customer_id)
                       ->with('company')->latest('order_date')->get();

        return Pdf::loadView('orders.pdf', compact('orders'))
                  ->download('siparisler.pdf');
    }

    public function exportPdfWithFilter(Request $r)
    {
        $start = $r->query('start'); $end = $r->query('end');
        if (!($start && $end)) return back()->with('warning','Tarih aralığı zorunlu');

        $orders = Order::where('customer_id', Auth::user()->customer_id)
                       ->whereBetween('order_date', [$start,$end])
                       ->with('company')->orderBy('order_date')->get();

        return Pdf::loadView('orders.pdf', [
            'orders'=>$orders,
            'range'=>[
                Carbon::parse($start)->format('d.m.Y'),
                Carbon::parse($end)->format('d.m.Y')
            ]
        ])->download("siparisler_{$start}_{$end}.pdf");
    }
}
