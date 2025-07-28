@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">

      {{-- Başlık + Arama --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Satış Raporu</h3>
        <input type="text" id="salesSearch"
               class="form-control"
               placeholder="Satışlarda ara..."
               style="max-width:250px;">
      </div>

      {{-- Grafikler --}}
      <div class="card-body">
        <div class="row text-center">
          {{-- Sol: Ciro Dağılımı --}}
          <div class="col-md-6 d-flex justify-content-center">
            <canvas id="revenueDonutChart"
                    style="height:220px; max-width:320px;"></canvas>
          </div>
          {{-- Sağ: Ürün Adet Dağılımı --}}
          <div class="col-md-6 d-flex justify-content-center">
            <canvas id="qtyBarChart"
                    style="height:220px; max-width:380px;"></canvas>
          </div>
        </div>
      </div>

      {{-- Tablo --}}
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="salesTable">
            <thead>
              <tr>
                <th>Firma</th>
                <th>Ürün</th>
                <th>Sipariş Tarihi</th>
                <th>Toplam Tutar</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $o)
                <tr>
                  <td>{{ $o->company->company_name ?? '—' }}</td>
                  <td>{{ $o->products->pluck('product_name')->implode(', ') }}</td>
                  <td>{{ $o->order_date->format('Y-m-d') }}</td>
                  <td>{{ number_format($o->total_amount, 2) }}</td>
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
document.addEventListener('DOMContentLoaded', () => {
  // 1) Sol Grafik: Ciro Dağılımı
  const revLabels = @json($revenueData->pluck('company'));
  const revData   = @json($revenueData->pluck('revenue'));

  new Chart(document.getElementById('revenueDonutChart'), {
    type: 'doughnut',
    data: {
      labels: revLabels,
      datasets: [{
        data: revData,
        backgroundColor: revLabels.map((_,i)=>`hsl(${(i*57)%360},70%,60%)`)
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom' },
        tooltip: {
          callbacks: {
            label: ctx => ` ${ctx.label}: ${ctx.parsed.toLocaleString()} ₺`
          }
        }
      }
    }
  });

  // 2) Sağ Grafik: Ürün Adet Dağılımı (Stacked Bar)
  const compLabels = @json($companies);
  const datasets   = @json($qtyDatasets);

  new Chart(document.getElementById('qtyBarChart'), {
    type: 'bar',
    data: { labels: compLabels, datasets: datasets },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: { stacked: true },
        y: {
          stacked: true,
          beginAtZero: true,
          title: { display: true, text: 'Adet' }
        }
      },
      plugins: {
        tooltip: {
          mode: 'index',
          intersect: false,
          callbacks: {
            label: item => ` ${item.dataset.label}: ${item.parsed.y.toLocaleString()} adet`,
            footer: items => {
              const sum = items.reduce((s, i) => s + i.parsed.y, 0);
              return 'Firma Toplamı: ' + sum.toLocaleString() + ' adet';
            }
          }
        }
      }
    }
  });

  // Tablo Arama
  document.getElementById('salesSearch').addEventListener('keyup', e => {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('#salesTable tbody tr').forEach(tr => {
      tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
  });
});
</script>
@endpush
