@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">

      {{-- Başlık + Arama --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Ürün Stok Raporu</h3>
        <input type="text" id="stockSearch"
               class="form-control"
               placeholder="Ürünlerde ara..."
               style="max-width:250px;">
      </div>

      {{-- Grafikler --}}
      <div class="card-body">
        <div class="row text-center">
          {{-- Pasta Grafiği (available stock) --}}
          <div class="col-md-6 d-flex justify-content-center">
            <canvas id="stockDonutChart" style="height:220px; max-width:320px;"></canvas>
          </div>
          {{-- Çubuk Grafiği (available stock) --}}
          <div class="col-md-6 d-flex justify-content-center">
            <canvas id="stockBarChart" style="height:220px; max-width:380px;"></canvas>
          </div>
        </div>
      </div>

      {{-- Tablo --}}
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="stockTable">
            <thead>
              <tr>
                <th>Ürün</th>
                <th>Mevcut Stok</th>
                <th>Güncelleme Tarihi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($stocks as $s)
                <tr>
                  <td>{{ $s->product->product_name }}</td>
                  <td>{{ $s->available }}</td>
                  <td>{{ $s->update_date->format('Y-m-d') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center p-4">Veri bulunamadı</td>
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
document.addEventListener('DOMContentLoaded', () => {
  const labels = @json($stocks->pluck('product.product_name'));
  const data   = @json($stocks->pluck('available'));

  // Donut Chart
  new Chart(document.getElementById('stockDonutChart'), {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{ 
        data: data,
        backgroundColor: labels.map((_,i)=>`hsl(${(i*57)%360},70%,60%)`)
      }]
    },
    options: {
      responsive:true,
      maintainAspectRatio:false,
      plugins:{ legend:{ position:'bottom' } }
    }
  });

  // Bar Chart
  new Chart(document.getElementById('stockBarChart'), {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Mevcut Stok',
        data: data,
        backgroundColor: '#e74a3b'
      }]
    },
    options: {
      responsive:true,
      maintainAspectRatio:false,
      scales:{ y:{ beginAtZero:true, title:{ display:true, text:'Adet' } } }
    }
  });

  // Tablo Arama
  document.getElementById('stockSearch').addEventListener('keyup', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#stockTable tbody tr').forEach(tr => {
      tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
  });
});
</script>
@endpush
