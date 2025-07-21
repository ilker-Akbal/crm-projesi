@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Sales Report</h3>
        <!-- Hızlı Arama (Sağa Hizalı) -->
        <div class="ml-auto">
          <input type="text" id="salesSearch" class="form-control" placeholder="Search sales..." style="max-width: 250px;">
        </div>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <!-- Donut Chart -->
          <div class="col-md-6 d-flex justify-content-center">
            <canvas id="salesDonutChart" style="height:220px; max-width:320px;"></canvas>
          </div>
          <!-- Bar Chart -->
          <div class="col-md-6 d-flex justify-content-center">
            <canvas id="salesBarChart" style="height:220px; max-width:380px;"></canvas>
          </div>
        </div>
      </div>

      <!-- Tablo -->
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="salesTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Order Date</th>
                <th>Total Amount</th>
              </tr>
            </thead>
            <tbody>
              @forelse($sales as $s)
                <tr>
                  <td>{{ $s->id }}</td>
                  <td>{{ $s->customer->customer_name }}</td>
                  <td>{{ $s->order_date }}</td>
                  <td>{{ number_format($s->total_amount, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center p-4">No data found</td>
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
  const customers = @json($sales->pluck('customer.customer_name'));
  const totals = @json($sales->pluck('total_amount'));

  // Donut Chart (Satışların oranı)
  const ctxDonut = document.getElementById('salesDonutChart').getContext('2d');
  new Chart(ctxDonut, {
    type: 'doughnut',
    data: {
      labels: customers,
      datasets: [{
        data: totals,
        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' } }
    }
  });

  // Bar Chart (Müşteri bazlı satışlar)
  const ctxBar = document.getElementById('salesBarChart').getContext('2d');
  new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: customers,
      datasets: [{
        label: 'Total Sales',
        data: totals,
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
  document.getElementById('salesSearch').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#salesTable tbody tr');
    rows.forEach(row => {
      const rowText = row.innerText.toLowerCase();
      row.style.display = rowText.includes(searchValue) ? '' : 'none';
    });
  });
});
</script>
@endpush
