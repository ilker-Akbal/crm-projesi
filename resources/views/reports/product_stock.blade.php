@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <!-- Kart Başlığı + Hızlı Arama -->
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Ürün Stok Raporu</h3>
        <div class="ml-auto">
          <input type="text" id="stockSearch" class="form-control" placeholder="Ürünlerde ara..." style="max-width: 250px;">
        </div>
      </div>

      <!-- Grafikler -->
      <div class="card-body">
        <div class="row text-center">
          <!-- Pasta Grafiği -->
          <div class="col-md-6 d-flex justify-content-center">
            <canvas id="stockDonutChart" style="height:220px; max-width:320px;"></canvas>
          </div>
          <!-- Çubuk Grafiği -->
          <div class="col-md-6 d-flex justify-content-center">
            <canvas id="stockBarChart" style="height:220px; max-width:380px;"></canvas>
          </div>
        </div>
      </div>

      <!-- Tablo -->
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="stockTable">
            <thead>
              <tr>
                
                <th>Ürün</th>
                <th>Stok Miktarı</th>
                <th>Güncelleme Tarihi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($stocks as $s)
                <tr class="{{ $s->stock_quantity < 10 ? 'table-danger' : '' }}">
                  
                  <td>{{ $s->product->product_name }}</td>
                  <td>{{ $s->stock_quantity }}</td>
                  <td>{{ $s->update_date }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center p-4">Veri bulunamadı</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const productNames = @json($stocks->pluck('product.product_name'));
  const stockQuantities = @json($stocks->pluck('stock_quantity'));

  // Pasta Grafiği (Stok Dağılımı)
  const ctxDonut = document.getElementById('stockDonutChart').getContext('2d');
  new Chart(ctxDonut, {
    type: 'doughnut',
    data: {
      labels: productNames,
      datasets: [{
        data: stockQuantities,
        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' } }
    }
  });

  // Çubuk Grafiği (Stok Karşılaştırma)
  const ctxBar = document.getElementById('stockBarChart').getContext('2d');
  new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: productNames,
      datasets: [{
        label: 'Stok Miktarı',
        data: stockQuantities,
        backgroundColor: stockQuantities.map(qty => qty < 10 ? '#e74a3b' : '#4e73df') // Kritik stokları kırmızı göster
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: { y: { beginAtZero: true } }
    }
  });

  // Hızlı Arama (Tablo Filtreleme)
  document.getElementById('stockSearch').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#stockTable tbody tr');
    rows.forEach(row => {
      const rowText = row.innerText.toLowerCase();
      row.style.display = rowText.includes(searchValue) ? '' : 'none';
    });
  });
});
</script>
@endpush
