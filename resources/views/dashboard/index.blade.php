@extends('layouts.app')

@section('content')
<div class="container-fluid">

  {{-- Sayaç kartları --}}
  <div class="row">
  @foreach($metrics as $label => $value)
    <div class="col-md-2 mb-3">
      <div class="card text-center h-100 shadow-sm">
        <div class="card-body">
          <h2 class="fw-bold">{{ $value }}</h2>
          <span class="text-muted">{{ ucfirst($label) }}</span>
        </div>
      </div>
    </div>
  @endforeach
</div>

  {{-- Gelir grafiği --}}
  <div class="card mb-4">
    <div class="card-header">Aylık Gelir</div>
    <div class="card-body">
      <canvas id="revenueChart"></canvas>
    </div>
  </div>

  {{-- En çok satılan ürünler --}}
  <div class="card mb-4">
    <div class="card-header">En Çok Satılan 5 Ürün</div>
    <div class="card-body">
      <canvas id="topProducts"></canvas>
    </div>
  </div>

  {{-- Yaklaşan teslimler & Stok uyarıları --}}
  <div class="row">
    <div class="col-md-6">
      <div class="card h-100 mb-4">
        <div class="card-header">Yaklaşan Teslimatlar (10 gün)</div>
        <table class="table table-sm mb-0">
          @foreach($upcoming as $o)
            <tr>
              <td>#{{ $o->id }}</td>
              <td>{{ $o->customer->customer_name }}</td>
              <td>{{ $o->delivery_date->format('d.m') }}</td>
            </tr>
          @endforeach
        </table>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card h-100 mb-4">
        <div class="card-header">Düşük Stok (<20)</div>
        <table class="table table-sm mb-0">
          @foreach($lowStock as $p)
            <tr>
              <td>{{ $p->product_name }}</td>
              <td>{{ $p->stocks->last()->stock_quantity ?? 0 }}</td>
            </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
/* Aylık Gelir Grafiği */
new Chart(document.getElementById('revenueChart'), {
  type: 'line',
  data: {
    labels: @json($revenue->keys()),
    datasets: [{
      label: '₺',
      data: @json($revenue->values()),
      fill: true,
    }]
  }
});

/* Top Products Grafiği */
new Chart(document.getElementById('topProducts'), {
  type: 'bar',
  data: {
    labels: @json($topProducts->pluck('product.product_name')),
    datasets: [{
      label: 'Adet',
      data: @json($topProducts->pluck('qty')),
    }]
  }
});
</script>
@endpush
