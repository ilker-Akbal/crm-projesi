@extends('layouts.app')

@section('content')
@php
  // Metrik başlıkları
  $metricLabels = [
    'customers'      => 'Müşteriler',
    'openOffers'     => 'Açık Teklifler',
    'openOrders'     => 'Açık Siparişler',
    'todayReminders' => 'Bugünkü Hatırlatmalar',
    'openSupports'   => 'Bekleyen Destek Talepleri',
  ];
@endphp

<style>
  /* Dashboard grid */
  .dashboard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(2, 1fr);
    gap: 1rem;
    padding: 1rem;
    height: auto;
  }

  /* Mobilde tek sütuna in */
  @media (max-width: 768px) {
    .dashboard-grid {
      grid-template-columns: 1fr;
      grid-template-rows: auto;
    }
    .dashboard-card {
      min-height: auto;
    }
  }

  .dashboard-card {
    background: #fff;
    border-radius: .5rem;
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  .dashboard-card-header {
    padding: .75rem 1rem;
    border-bottom: 1px solid #dee2e6;
    font-weight: 600;
    background: #f8f9fa;
  }

  .dashboard-card-body {
    flex: 1;
    padding: 1rem;
    overflow: auto;
  }

  /* Genel Durum kısmı */
  .metrics-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 1rem;
  }

  .metric-card .card {
    height: 100%;
    border-radius: .5rem;
    border: none;
    background: #fdfdfd;
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.05);
    transition: transform 0.2s ease;
  }

  .metric-card .card:hover {
    transform: translateY(-3px);
  }

  .metric-card .card-body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .metric-card h2 {
    font-size: 1.8rem;
    margin-bottom: .25rem;
  }

  /* List-group (yaklaşan teslimatlar) */
  .list-group-item {
    border: none;
    border-radius: .25rem;
    margin-bottom: .5rem;
    background: #fdfdfd;
    box-shadow: 0 .0625rem .125rem rgba(0,0,0,.05);
  }

  /* Grafiklerin responsive görünümü */
  .dashboard-card-body canvas {
    max-width: 100%;
    height: auto !important;
  }
</style>

<div class="dashboard-grid">
  <!-- 1. Bölüm: Genel Durum (Metrikler) -->
  <div class="dashboard-card">
    <div class="dashboard-card-header">Genel Durum</div>
    <div class="dashboard-card-body metrics-container">
      @foreach($metrics as $key => $value)
        @if($key === 'customers') @continue @endif
        <div class="metric-card">
          <div class="card h-100">
            <div class="card-body text-center">
              <h2 class="fw-bold text-primary">{{ $value }}</h2>
              <span class="text-muted">{{ $metricLabels[$key] }}</span>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- 2. Bölüm: Aylık Gelir -->
  <div class="dashboard-card">
    <div class="dashboard-card-header">Aylık Gelir</div>
    <div class="dashboard-card-body">
      <canvas id="revenueChart"></canvas>
    </div>
  </div>

  <!-- 3. Bölüm: En Çok Satılan Ürünler -->
  <div class="dashboard-card">
    <div class="dashboard-card-header">En Çok Satılan 5 Ürün</div>
    <div class="dashboard-card-body">
      <canvas id="topProducts"></canvas>
    </div>
  </div>

  <!-- 4. Bölüm: Yaklaşan Teslimatlar -->
  <div class="dashboard-card">
    <div class="dashboard-card-header">Yaklaşan Teslimatlar</div>
    <div class="dashboard-card-body">
      @if($upcoming->isEmpty())
        <p class="text-muted text-center">Yaklaşan teslimat yok</p>
      @else
        <ul class="list-group">
          @foreach($upcoming as $o)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <a href="{{ route('orders.show', $o) }}" class="fw-bold text-decoration-none">
                  Sipariş #{{ $o->id }}
                </a>
                <span class="ms-2 text-muted">{{ $o->customer->customer_name }}</span>
              </div>
              <span class="badge bg-primary">
                {{ \Carbon\Carbon::parse($o->delivery_date)->format('d.m.Y') }}
              </span>
            </li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Aylık Gelir Grafiği
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

  // En Çok Satılan Ürünler Grafiği
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
    options: { responsive: true, maintainAspectRatio: false }
  });
</script>
@endpush
