<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use App\Models\ProductStock;
use App\Models\Customer;
use App\Models\CurrentCard;
use App\Models\SupportRequest;

class ReportController extends Controller
{
    /* -------------------------------------------------
     |  Yardımcı: QuickChart nesnesini data-URI'ye çevir
     * ------------------------------------------------*/
    private function qcToDataUri(\QuickChart $chart): string
    {
        $png = file_get_contents($chart->getUrl());          // ham PNG
        return 'data:image/png;base64,' . base64_encode($png);
    }

    /* -------------------------------------------------
     |  Satış Raporu (web ekranı)
     * ------------------------------------------------*/
    public function sales(Request $request)
    {
        $customerId = Auth::user()->customer_id;

        // Tablo verisi
        $orders = Order::with(['company', 'products'])
                       ->where('customer_id', $customerId)
                       ->orderByDesc('order_date')
                       ->get();

        // Ciro (donut)
        $revenueData = Order::selectRaw(
                            'companies.company_name AS company,
                             SUM(orders.total_amount) AS revenue'
                        )
                        ->join('companies', 'orders.company_id', '=', 'companies.id')
                        ->where('orders.customer_id', $customerId)
                        ->groupBy('companies.company_name')
                        ->orderByDesc('revenue')
                        ->get();

        // Ciro Grafiği (optimize edilmiş versiyon)
        $revBar = new \QuickChart([
            'width' => 700,
            'height' => 400,
            'devicePixelRatio' => 2.0
        ]);

        $revBar->setConfig([
            'type' => 'bar',
            'data' => [
                'labels' => $revenueData->pluck('company')->all(),
                'datasets' => [[
                    'label' => 'Ciro (₺)',
                    'data' => $revenueData->pluck('revenue')->all(),
                    'backgroundColor' => $revenueData->pluck('company')
                        ->map(fn($c,$i) => "hsl(".($i*57%360).",70%,60%)")->all(),
                    'barThickness' => 35,
                ]],
            ],
            'options' => [
                'layout' => [
                    'padding' => [
                        'top' => 20,
                        'right' => 15,
                        'bottom' => 40,
                        'left' => 30
                    ]
                ],
                'plugins' => [
                    'legend' => ['display' => false],
                    'datalabels' => [
                        'color' => '#000',
                        'anchor' => 'end',
                        'align' => 'top',
                        'font' => ['weight' => 'bold', 'size' => 11],
                        'formatter' => 'v => Math.round(v).toLocaleString()+" ₺"',
                    ],
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'padding' => 10,
                            'font' => ['size' => 10]
                        ]
                    ],
                    'x' => [
                        'ticks' => [
                            'font' => ['size' => 9],
                            'maxRotation' => 45,
                            'minRotation' => 45
                        ]
                    ]
                ]
            ]
        ]);

        $revenueChartUrl = $this->qcToDataUri($revBar);

        // Ürün adet (stacked bar)
        $rawQty = Order::join('companies', 'orders.company_id', '=', 'companies.id')
                       ->join('order_products', 'orders.id', '=', 'order_products.order_id')
                       ->join('products', 'order_products.product_id', '=', 'products.id')
                       ->selectRaw(
                           'companies.company_name AS company,
                            products.product_name  AS product,
                            SUM(order_products.amount) AS total_qty'
                       )
                       ->where('orders.customer_id', $customerId)
                       ->groupBy('companies.company_name', 'products.product_name')
                       ->get();

        $companies   = $rawQty->pluck('company')->unique()->values();
        $products    = $rawQty->pluck('product')->unique()->values();

        $qtyDatasets = $products->map(function ($product, $idx) use ($rawQty, $companies) {
            return [
                'label'           => $product,
                'data'            => $companies->map(function ($company) use ($rawQty, $product) {
                                        $row = $rawQty->first(fn ($r) =>
                                            $r->company === $company && $r->product === $product
                                        );
                                        return $row->total_qty ?? 0;
                                    })->values()->all(),
                'backgroundColor' => "hsl(" . (($idx * 57) % 360) . ",70%,60%)",
                'stack'           => 'stack1',
            ];
        });

        return view('reports.sales', compact(
            'orders',
            'revenueData',
            'companies',
            'qtyDatasets',
            'revenueChartUrl'
        ));
    }
    /**
 * Tarih aralığına göre satış raporu PDF’i
 *  route: reports.sales.pdf.filter
 *  GET ?start=YYYY-MM-DD&end=YYYY-MM-DD
 */
public function salesPdfFilter(Request $request)
{
    /* ---------- 1) Gelen veriyi doğrula ---------- */
    $request->validate([
        'start' => ['required', 'date'],
        'end'   => ['required', 'date', 'after_or_equal:start'],
    ]);
    $start = $request->input('start');
    $end   = $request->input('end');

    /* ---------- 2) Tablo verisi (sadece aralıktaki siparişler) ---------- */
    $orders = Order::with(['company', 'products'])
                   ->where('customer_id', Auth::user()->customer_id)
                   ->whereBetween('order_date', [$start, $end])
                   ->orderByDesc('order_date')
                   ->get();

    /* ---------- 3) Şirket ciroları (aynı aralık) ---------- */
    $revenueData = Order::selectRaw(
                        'companies.company_name AS company,
                         SUM(orders.total_amount) AS revenue')
                    ->join('companies', 'orders.company_id', '=', 'companies.id')
                    ->where('orders.customer_id', Auth::user()->customer_id)
                    ->whereBetween('order_date', [$start, $end])
                    ->groupBy('companies.company_name')
                    ->orderByDesc('revenue')
                    ->get();

    /* ---------- 4) Ciro Bar Grafiği (650 × 320 SVG) ---------- */
    $revBar = new \QuickChart(['width' => 650, 'height' => 320]);
    $revBar->setConfig([
        'type'  => 'bar',
        'data'  => [
            'labels'   => $revenueData->pluck('company')->all(),
            'datasets' => [[
                'label'        => 'Ciro (₺)',
                'data'         => $revenueData->pluck('revenue')->all(),
                'barThickness' => 30,
                'backgroundColor' => $revenueData->pluck('company')
                    ->map(fn ($c, $i) => "hsl(" . (($i * 57) % 360) . ",70%,60%)")
                    ->all(),
            ]],
        ],
        'options' => [
            'plugins' => [
                'legend'     => ['display' => false],
                'datalabels' => [
                    'anchor'    => 'end',
                    'align'     => 'start',
                    'color'     => '#000',
                    'font'      => ['weight' => 'bold', 'size' => 12],
                    'formatter' => 'v => v.toLocaleString()+" ₺"',
                ],
            ],
            'plugins_list' => ['datalabels'],
            'scales' => [
                'y' => ['beginAtZero' => true],
            ],
        ],
    ]);
    $revBar->setFormat('svg');
    $revenueUrl = $revBar->getUrl();

    /* ---------- 5) Logo (SVG → base64) ---------- */
    $logoPath = public_path('images/ika_logo.svg');
    $logoData = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($logoPath));

    /* ---------- 6) Toplam Ciro ---------- */
    $ordersTotal = $orders->sum('total_amount');

    /* ---------- 7) PDF ---------- */
    return Pdf::loadView('reports.sales_pdf', [
            'orders'       => $orders,
            'ordersTotal'  => $ordersTotal,
            'revenueUrl'   => $revenueUrl,
            'logoData'     => $logoData,
            'companyInfo'  => [
                'address' => 'İstiklal Cd. No:123 İstanbul',
                'tax'     => 'Vergi No: 1234567890',
                'phone'   => '+90 212 555 00 00',
                'email'   => 'info@ikacrm.com',
            ],
        ])
        ->setPaper('a4', 'portrait')
        ->download('sales-report-' . now()->format('Ymd') . '.pdf');
}

/* -------------------------------------------------
 |  Ürün Stok Raporu (web ekranı)
 * ------------------------------------------------*/
public function productStock()
    {
        $customerId = Auth::user()->customer_id;

        // En güncel stoklar
        $latestStocks = ProductStock::whereHas('product', fn ($q) =>
                                $q->where('customer_id', $customerId))
                            ->with('product')
                            ->orderByDesc('update_date')
                            ->get()
                            ->unique('product_id')
                            ->values();

        // Satılan adetler
        $sold = DB::table('order_products')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->where('orders.customer_id', $customerId)
            ->where('orders.order_type', Order::SALE)
            ->select('order_products.product_id', DB::raw('SUM(order_products.amount) AS sold_qty'))
            ->groupBy('order_products.product_id')
            ->pluck('sold_qty', 'product_id');

        // Kullanılabilir stok hesapla
        foreach ($latestStocks as $stock) {
            $available  = $stock->stock_quantity
                        - $stock->reserved_stock
                        - $stock->blocked_stock;
            $stock->available = max(0, $available);
        }

        return view('reports.product_stock', ['stocks' => $latestStocks]);
    }

    /* -------------------------------------------------
     |  Satış Raporu - PDF
     * ------------------------------------------------*/
    public function salesPdf(Request $request)
    {
        $customerId = Auth::user()->customer_id;

        /* ---------- Tablo verisi ---------- */
        $orders = Order::with(['company','products'])
                       ->where('customer_id',$customerId)
                       ->orderByDesc('order_date')
                       ->get();

        /* ---------- Şirket ciroları ---------- */
        $revenueData = Order::selectRaw(
                            'companies.company_name AS company,
                             SUM(orders.total_amount) AS revenue')
                        ->join('companies','orders.company_id','=','companies.id')
                        ->where('orders.customer_id',$customerId)
                        ->groupBy('companies.company_name')
                        ->orderByDesc('revenue')
                        ->get();

        /* ---------- Grafik ---------- */
        $revBar = new \QuickChart(['width'=>650,'height'=>320]);
        $revBar->setConfig([
            'type'  => 'bar',
            'data'  => [
                'labels'   => $revenueData->pluck('company')->all(),
                'datasets' => [[
                    'label'           => 'Ciro (₺)',
                    'data'            => $revenueData->pluck('revenue')->all(),
                    'backgroundColor' => $revenueData->pluck('company')
                        ->map(fn($c,$i)=>"hsl(".(($i*57)%360).",70%,60%)")->all(),
                    'barThickness'    => 30,
                ]],
            ],
            'options'=>[
                'plugins'=>[
                    'legend'=>['display'=>false],
                    'datalabels'=>[
                        'color'=>'#000',
                        'anchor'=>'end','align'=>'start',
                        'font'=>['weight'=>'bold','size'=>12],
                        'formatter'=>'v => v.toLocaleString()+" ₺"',
                    ],
                ],
                'plugins_list'=>['datalabels'],
                'scales'=>[
                    'y'=>['beginAtZero'=>true],
                ],
            ],
        ]);
        $revBar->setFormat('svg');
        $revenueUrl = $revBar->getUrl();

        /* ---------- Logo ---------- */
        $logoPath = public_path('images/ika_logo.svg');
        $logoData = 'data:image/svg+xml;base64,'.base64_encode(file_get_contents($logoPath));

        /* ---------- Toplam ciro ---------- */
        $ordersTotal = $orders->sum('total_amount');

        /* ---------- PDF ---------- */
        return Pdf::loadView('reports.sales_pdf', [
                'orders'       => $orders,
                'ordersTotal'  => $ordersTotal,
                'revenueUrl'   => $revenueUrl,
                'logoData'     => $logoData,
                'companyInfo'  => [
                    'address' => 'İstiklal Cd. No:123 İstanbul',
                    'tax'     => 'Vergi No: 1234567890',
                    'phone'   => '+90 212 555 00 00',
                    'email'   => 'info@ikacrm.com',
                ],
            ])
            ->setPaper('a4','portrait')
            ->download('sales-report-'.now()->format('Ymd').'.pdf');
    }

    /* -------------------------------------------------
     |  Diğer rapor metotları
     * ------------------------------------------------*/
    public function customers()              { /* … */ }
    public function currentAccountSummary()  { /* … */ }
    public function supportRequest()         { /* … */ }

    /* CRUD iskeletleri (boş) */
    public function index()      {}
    public function create()     {}
    public function store()      {}
    public function show()       {}
    public function edit()       {}
    public function update()     {}
    public function destroy()    {}
}