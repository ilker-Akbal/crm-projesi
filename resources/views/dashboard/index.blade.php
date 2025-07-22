@extends('layouts.app')

@section('content')
<style>
  /* 2x2 grid kaplayan tam ekran dashboard */
  .dashboard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(2, 1fr);
    gap: 1rem;
    height: calc(100vh - 2rem); /* navbar/footer yüksekliğine göre ayarlayın */
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

  /* 1. Quadrant (Genel Durum) içindeki scroll’u kaldır ve grid’e çevir */
  .dashboard-grid > .dashboard-card:nth-child(1) .dashboard-card-body {
    overflow: hidden;
    padding: 1rem;
  }
  .metrics-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
    height: 90%;
  }
  .metric-card .card {
    height: 70%;
  }
  .metric-card .card-body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
</style>

<div class="dashboard-grid">
  <!-- 1. Bölüm: Metrikler -->
  <div class="dashboard-card">
    <div class="dashboard-card-header">Genel Durum</div>
    <div class="dashboard-card-body metrics-container">
      @foreach($metrics as $label => $value)
        <div class="metric-card">
          <div class="card">
            <div class="card-body text-center">
              <h2 class="fw-bold">{{ $value }}</h2>
              <span class="text-muted">{{ ucfirst($label) }}</span>
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
    <div class="dashboard-card-header">Uyarılar</div>
    <div class="dashboard-card-body">
      <div class="row h-100 g-2">
        <!-- Yaklaşan Teslimatlar -->
        <div class="col-6 d-flex flex-column">
          <h6>Yaklaşan Teslimatlar</h6>
          <div class="flex-grow-1 overflow-auto">
            <table class="table table-sm mb-0">
              <tbody>
                @foreach($upcoming as $o)
                  <tr>
                    <td>#{{ $o->id }}</td>
                    <td>{{ $o->customer->customer_name }}</td>
                    <td>{{ $o->delivery_date->format('d.m.Y') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <!-- Düşük Stok -->
        <div class="col-6 d-flex flex-column">
          <h6>Düşük Stok</h6>
          <div class="flex-grow-1 overflow-auto">
            <table class="table table-sm mb-0">
              <tbody>
                @foreach($lowStock as $p)
                  <tr>
                    <td>{{ $p->product_name }}</td>
                    <td>{{ $p->stocks->last()->stock_quantity ?? 0 }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
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
