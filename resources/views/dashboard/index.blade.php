@extends('layouts.app')

@section('content')
@php
    $metricLabels = [
        'customers'      => 'Müşteriler',
        'openOffers'     => 'Açık Teklifler',
        'openOrders'     => 'Açık Siparişler',
        'todayReminders' => 'Bugünkü Hatırlatmalar',
        'openSupports'   => 'Bekleyen Destek Talepleri',
    ];
@endphp

<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        padding: 1rem;
    }
    @media (max-width: 768px) {
        .dashboard-grid { grid-template-columns: 1fr; }
    }
    .dashboard-card {
        background: #fff;
        border-radius: .5rem;
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.1);
        display: flex;
        flex-direction: column;
    }
    .dashboard-card-header {
        padding: .75rem 1rem;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
        background: #f8f9fa;
    }
    .dashboard-card-body { padding: 1rem; height: 100%; }
    .metrics-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
    }
    .metric-card .card {
        height: 100%;
        border-radius: .5rem;
        background: #fdfdfd;
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.05);
        transition: transform 0.2s ease;
    }
    .metric-card .card:hover { transform: translateY(-3px); }
    .metric-card h2 { font-size: 1.8rem; margin-bottom: .25rem; }
    .list-group-item {
        border: none;
        border-radius: .25rem;
        margin-bottom: .5rem;
        background: #fdfdfd;
        box-shadow: 0 .0625rem .125rem rgba(0,0,0,.05);
    }
    canvas { max-width: 100%; height: 300px !important; }

    /* Paginasyon için eklenen ve güncellenen stiller */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
    }
    .pagination a, .pagination span {
        display: inline-block;
        padding: .375rem .75rem;
        margin: 0 .25rem;
        border: 1px solid #dee2e6;
        border-radius: .25rem;
        text-decoration: none;
        color: #0d6efd;
    }
    .pagination .active a, .pagination a:hover {
        background-color: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }
    .pagination .disabled {
        color: #6c757d;
        pointer-events: none;
        background-color: #f8f9fa;
    }
</style>

<div class="dashboard-grid">
    {{-- 1) Genel Durum --}}
    <div class="dashboard-card">
        <div class="dashboard-card-header">Genel Durum</div>
        <div class="dashboard-card-body metrics-container">
            @foreach($metrics as $key => $value)
                @if($key === 'customers') @continue @endif
                <div class="metric-card">
                    <div class="card">
                        <div class="card-body text-center">
                            <h2 class="fw-bold text-primary">{{ $value }}</h2>
                            <span class="text-muted">{{ $metricLabels[$key] ?? ucfirst($key) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- 2) Aylık Gelir --}}
    <div class="dashboard-card">
        <div class="dashboard-card-header">Aylık Gelir</div>
        <div class="dashboard-card-body">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- 3) En Çok Satılan Ürünler --}}
    <div class="dashboard-card">
        <div class="dashboard-card-header">En Çok Satılan 5 Ürün</div>
        <div class="dashboard-card-body">
            <canvas id="topProducts"></canvas>
        </div>
    </div>

    {{-- 4) Yaklaşan Teslimatlar --}}
<div class="dashboard-card">
    <div class="dashboard-card-header">Yaklaşan Teslimatlar</div>
    <div class="dashboard-card-body">
        @if($upcoming->isEmpty())
            <p class="text-muted text-center">Yaklaşan teslimat yok</p>
        @else
            <ul class="list-group mb-3">
                @foreach($upcoming as $o)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('orders.show', $o) }}" class="fw-bold text-decoration-none">
                                Sipariş #{{ $o->id }}
                            </a>
                        </div>
                        <span class="badge bg-primary">
                            {{ \Carbon\Carbon::parse($o->delivery_date)->format('d.m.Y') }}
                        </span>
                    </li>
                @endforeach
            </ul>
            <div class="d-flex justify-content-center">
                {{ $upcoming->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Aylık Gelir
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($revenue->keys()),
            datasets: [{
                label: '₺',
                data: @json($revenue->values()),
                fill: true,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Top Products
    new Chart(document.getElementById('topProducts'), {
        type: 'bar',
        data: {
            labels: @json($topProducts->pluck('product.product_name')),
            datasets: [{
                label: 'Adet',
                data: @json($topProducts->pluck('qty')),
                backgroundColor: 'rgba(255, 206, 86, 0.6)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    ticks: {
                        // Etiketlerin yazı puntosu ayarı
                        font: {
                            size: 12 // Yazı puntosu boyutu. İsteğe göre değiştirebilirsiniz.
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: @json($topProducts->pluck('qty')->max() * 1.1)
                }
            }
        }
    });
</script>
@endpush