@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Cari Hesap Özeti</h3>
        <!-- Hızlı Arama (Sağa Hizalı) -->
        <div class="ml-auto">
          <input type="text" id="accountSearch" class="form-control" placeholder="Hesaplarda ara..." style="max-width: 250px;">
        </div>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <!-- Pasta Grafiği -->
          <div class="col-md-6 d-flex justify-content-center">
            <canvas id="accountDonutChart" style="height:220px; max-width:320px;"></canvas>
          </div>
          <!-- Çubuk Grafiği -->
          <div class="col-md-6 d-flex justify-content-center">
            <canvas id="accountBarChart" style="height:220px; max-width:380px;"></canvas>
          </div>
        </div>
      </div>

      <!-- Tablo (Grafiklerin altında) -->
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="accountTable">
            <thead>
              <tr>
                
                <th>Müşteri</th>
                <th>Bakiye</th>
                <th>Açılış Tarihi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($accounts as $a)
                <tr>
                  
                  <td>{{ $a->customer->customer_name }}</td>
                  <td>{{ number_format($a->balance, 2) }}</td>
                  <td>{{ $a->opening_date }}</td>
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
  const customers = @json($accounts->pluck('customer.customer_name'));
  const balances = @json($accounts->pluck('balance'));

  // Pasta Grafiği (Bakiye Dağılımı)
  const ctxDonut = document.getElementById('accountDonutChart').getContext('2d');
  new Chart(ctxDonut, {
    type: 'doughnut',
    data: {
      labels: customers,
      datasets: [{
        data: balances,
        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' } }
    }
  });

  // Çubuk Grafiği (Bakiye Karşılaştırma)
  const ctxBar = document.getElementById('accountBarChart').getContext('2d');
  new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: customers,
      datasets: [{
        label: 'Bakiye',
        data: balances,
        backgroundColor: '#4e73df'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: { y: { beginAtZero: true } }
    }
  });

  // Hızlı Arama (Tablo Filtreleme)
  document.getElementById('accountSearch').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#accountTable tbody tr');
    rows.forEach(row => {
      const rowText = row.innerText.toLowerCase();
      row.style.display = rowText.includes(searchValue) ? '' : 'none';
    });
  });
});
</script>
@endpush
