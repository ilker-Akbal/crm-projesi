@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">

      {{-- ▸▸ ÜST BAR: Başlık + Arama ---------------------------------------- --}}
      <div class="card-header d-flex align-items-center" style="margin-bottom:0;">
        <h3 class="card-title mb-0">Satış Raporu</h3>
        <input id="salesSearch"
               type="text"
               class="form-control form-control-sm ms-4"
               placeholder="Satışlarda ara..."
               style="max-width:220px;">
      </div>
      {{-- ▸▸ /ÜST BAR ------------------------------------------------------- --}}

      {{-- ==== PDF & Tarih Filtresi ==== --}}
      <div class="card-body">
        <form action="{{ route('reports.sales.pdf.filter') }}" method="GET"
              class="row g-2 align-items-end mb-3">

          {{-- Başlangıç tarihi --}}
          <div class="col-12 col-md-3">
            <label class="form-label mb-1 fw-semibold">Başlangıç</label>
            <input type="date" name="start" class="form-control" required>
          </div>

          {{-- Bitiş tarihi --}}
          <div class="col-12 col-md-3">
            <label class="form-label mb-1 fw-semibold">Bitiş</label>
            <input type="date" name="end" class="form-control" required>
          </div>

          {{-- Filtreli PDF --}}
          <div class="col-12 col-md-auto">
            <button type="submit" class="btn btn-danger w-100">
              Filtreli PDF İndir
            </button>
          </div>

          {{-- Tümünü PDF --}}
          <div class="col-12 col-md-auto ms-md-auto">
            <a href="{{ route('reports.sales.pdf') }}" class="btn btn-outline-secondary w-100">
              Tümünü PDF İndir
            </a>
          </div>
        </form>
      </div>

      {{-- ▸▸ GRAFİKLER ------------------------------------------------------- --}}
      <div class="card-body pt-0">
        <div class="row text-center">
          <div class="col-md-6 d-flex justify-content-center mb-3 mb-md-0">
            <canvas id="revenueDonutChart" style="height:220px; max-width:320px;"></canvas>
          </div>
          <div class="col-md-6 d-flex justify-content-center">
            <canvas id="qtyBarChart" style="height:220px; max-width:380px;"></canvas>
          </div>
        </div>
      </div>

      {{-- ▸▸ TABLO ----------------------------------------------------------- --}}
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
@php
  $revColors = [];
  foreach ($revenueData as $idx => $row) {
      $revColors[] = "hsl(" . (($idx * 57) % 360) . ",70%,60%)";
  }
@endphp
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  /* Ciro Donut */
  new Chart(document.getElementById('revenueDonutChart'), {
    type: 'doughnut',
    data: {
      labels: @json($revenueData->pluck('company')),
      datasets: [{ data: @json($revenueData->pluck('revenue')), backgroundColor: @json($revColors) }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' }, tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed.toLocaleString()} ₺` } } }
    }
  });

  /* Ürün Adet Bar */
  new Chart(document.getElementById('qtyBarChart'), {
    type: 'bar',
    data: { labels: @json($companies), datasets: @json($qtyDatasets) },
    options: {
      responsive: true, maintainAspectRatio: false,
      scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true, title: { display: true, text: 'Adet' } } },
      plugins: { tooltip: { mode: 'index', intersect: false, callbacks: { label: i => ` ${i.dataset.label}: ${i.parsed.y.toLocaleString()} adet`, footer: items => `Firma Toplamı: ${items.reduce((s,i)=>s+i.parsed.y,0).toLocaleString()} adet` } } }
    }
  });

  /* Tablo Arama */
  document.getElementById('salesSearch').addEventListener('keyup', e => {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('#salesTable tbody tr').forEach(tr => tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none');
  });
});
</script>
@endpush


@push('styles')
<style>
@media (max-width: 768px) {
  .card-header { flex-direction: column !important; gap: 10px !important; }
  #salesSearch { max-width: 100% !important; width: 100%; }
}
.btn-danger { background: #dc3545; border-color: #dc3545; }
.btn-outline-secondary { color: #6c757d; border-color: #6c757d; }
.btn-danger:hover { background: #c82333; border-color: #bd2130; }
.btn-outline-secondary:hover { background: #e2e6ea; border-color: #dae0e5; }
</style>
@endpush
