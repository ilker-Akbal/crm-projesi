@extends('layouts.app')

@section('content')
@php
  // Metric anahtarlarını insan okunur başlıklara çevirmek için
  $metricLabels = [
    'customers'      => 'Müşteriler',
    'openOffers'     => 'Açık Teklifler',
    'openOrders'     => 'Açık Siparişler',
    'todayReminders' => 'Bugünkü Hatırlatmalar',
    'openSupports'   => 'Bekleyen Destek Talepleri',
  ];
@endphp

<style>
  /* 2x2 grid kaplayan tam ekran dashboard */
  .dashboard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(2, 1fr);
    gap: 1rem;
    height: calc(100vh - 2rem);
    padding: 1rem;
  }
  .dashboard-card {
    background: #fff;
    border-radius: .25rem;
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }
  .dashboard-card-header {
    padding: .75rem 1rem;
    border-bottom: 1px solid #dee2e6;
    font-weight: 500;
    background: #f8f9fa;
  }
  .dashboard-card-body {
    flex: 1;
    padding: 1rem;
    overflow: auto;
  }
  /* Genel Durum’daki grid ayarı */
  .dashboard-grid > .dashboard-card:nth-child(1) .dashboard-card-body {
    overflow: hidden;
    padding: 1rem;
  }
  .metrics-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
    height: 100%;
  }
  .metric-card .card {
    height: 100%;
  }
  .metric-card .card-body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  /* List-group stili */
  .list-group-item {
    border: none;
    border-radius: .25rem;
    margin-bottom: .5rem;
  }
</style>

<div class="dashboard-grid">
  <!-- 1. Bölüm: Metrikler -->
  <div class="dashboard-card">
    <div class="dashboard-card-header">Genel Durum</div>
    <div class="dashboard-card-body metrics-container">
      @foreach($metrics as $key => $value)
        @if($key === 'customers') @continue @endif
        <div class="metric-card">
          <div class="card h-100">
            <div class="card-body text-center">
              <h2 class="fw-bold">{{ $value }}</h2>
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

    <!-- 4. Bölüm: Uyarılar -->
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
              <span class="ms-2">{{ $o->customer->customer_name }}</span>
            </div>
            {{-- Tarih rozetini mavi yaptık --}}
            <span class="badge bg-primary">
              {{ \Carbon\Carbon::parse($o->delivery_date)->format('d.m.Y') }}
            </span>
          </li>
        @endforeach
      </ul>
    @endif
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
        datasets: [{ label: '₺', data: @json($revenue->values()), fill: true }]
      }
    });

    // En Çok Satılan Ürünler Grafiği
    new Chart(document.getElementById('topProducts'), {
      type: 'bar',
      data: {
        labels: @json($topProducts->pluck('product.product_name')),
        datasets: [{ label: 'Adet', data: @json($topProducts->pluck('qty')) }]
      }
    });
  </script>
@endpush
