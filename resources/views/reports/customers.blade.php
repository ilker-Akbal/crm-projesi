@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Müşteri Raporu</h3>
        <!-- Hızlı Arama (Sağa Hizalı) -->
        <div class="ml-auto">
          <input type="text" id="customerSearch" class="form-control" placeholder="Müşterilerde ara..." style="max-width: 250px;">
        </div>
      </div>

      <!-- Grafik ve Toplam Bilgi -->
      <div class="card-body">
        <div class="row text-center">
          <!-- Pasta Grafiği -->
          <div class="col-md-6 d-flex justify-content-center">
            <canvas id="customerDonutChart" style="height:220px; max-width:320px;"></canvas>
          </div>
          <!-- Toplam Müşteri Kartı -->
          <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div style="font-size: 1.5rem; font-weight: bold; background:#f4f6f9; border-radius:8px; padding:20px;">
              Toplam Müşteri: {{ $customers->count() }}
            </div>
          </div>
        </div>
      </div>

      <!-- Tablo -->
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="customerTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Tür</th>
                <th>Telefon</th>
                <th>E-posta</th>
              </tr>
            </thead>
            <tbody>
              @forelse($customers as $c)
                <tr>
                  <td>{{ $c->id }}</td>
                  <td>{{ $c->customer_name }}</td>
                  <td>{{ $c->customer_type }}</td>
                  <td>{{ $c->phone }}</td>
                  <td>{{ $c->email }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center p-4">Veri bulunamadı</td>
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
  // Pasta Grafiği: Her müşteri farklı renk
  const customerNames = @json($customers->pluck('customer_name'));
  const counts = Array(customerNames.length).fill(1);

  const ctx = document.getElementById('customerDonutChart').getContext('2d');
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: customerNames,
      datasets: [{
        data: counts,
        backgroundColor: [
          '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
        ],
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' } }
    }
  });

  // Hızlı Arama (Tablo Filtreleme)
  document.getElementById('customerSearch').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#customerTable tbody tr');
    rows.forEach(row => {
      const rowText = row.innerText.toLowerCase();
      row.style.display = rowText.includes(searchValue) ? '' : 'none';
    });
  });
});
</script>
@endpush
