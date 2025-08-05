@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">

      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Siparişler</h3>
        <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">Yeni Sipariş Ekle</a>
      </div>

      {{-- PDF Formu --}}
      <form action="{{ route('orders.pdf.filter') }}" method="GET"
            class="row g-2 align-items-end mb-3 px-3 pt-3">

        <div class="col-12 col-md-3">
          <label class="form-label mb-1 fw-semibold">Başlangıç</label>
          <input type="date" name="start" class="form-control" required>
        </div>

        <div class="col-12 col-md-3">
          <label class="form-label mb-1 fw-semibold">Bitiş</label>
          <input type="date" name="end" class="form-control" required>
        </div>

        <div class="col-12 col-md-auto">
          <button class="btn btn-danger w-100">Filtreli PDF</button>
        </div>

        <div class="col-12 col-md-auto ms-md-auto">
          <a href="{{ route('orders.pdf') }}" class="btn btn-outline-secondary w-100">
            Tümünü PDF
          </a>
        </div>
      </form>

      <div class="card-body p-0">

        {{-- Masaüstü tablo --}}
        <div class="desktop-table table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Müşteri</th><th>Firma</th><th>Tip</th><th>Sipariş</th>
                <th>Teslim</th><th>Ödeme</th>
                <th class="text-end">Rezerve</th>
                <th class="text-end">Toplam</th><th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $o)
                @php
                  $reserveQty = ($o->order_type === 'sale' && ! $o->is_paid)
                                ? $o->products->sum('pivot.amount') : 0;
                @endphp
                <tr>
                  <td>{{ $o->customer->customer_name }}</td>
                  <td>{{ $o->company->company_name ?? '—' }}</td>
                  <td>
                    <span class="badge {{ $o->order_type==='sale' ? 'bg-info text-dark' : 'bg-secondary' }}">
                      {{ $o->order_type==='sale' ? 'Satış' : 'Alış' }}
                    </span>
                  </td>
                  <td>{{ $o->order_date?->format('d.m.Y') }}</td>
                  <td>{{ $o->delivery_date?->format('d.m.Y') ?? '—' }}</td>
                  <td>
                    @if($o->is_paid)
                      <span class="badge bg-success">Ödendi</span>
                    @else
                      <span class="badge bg-warning text-dark">Bekliyor</span>
                    @endif
                  </td>
                  <td class="text-end">{{ $reserveQty ?: '—' }}</td>
                  <td class="text-end">{{ number_format($o->total_amount,2) }} ₺</td>
                  <td>
                    <a href="{{ route('orders.show',$o) }}" class="btn btn-sm btn-info">Göster</a>

                    {{-- Düzenle / Sil her zaman görünsün; ödenmişse disabled --}}
                    @if($o->is_paid)
                      <button class="btn btn-sm btn-secondary" disabled>Düzenle</button>
                      <button class="btn btn-sm btn-secondary" disabled>Sil</button>
                    @else
                      <a href="{{ route('orders.edit',$o) }}" class="btn btn-sm btn-warning">Düzenle</a>
                      <form action="{{ route('orders.destroy',$o) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Silinsin mi?')" class="btn btn-sm btn-danger">Sil</button>
                      </form>
                    @endif
                  </td>
                </tr>
              @empty
                <tr><td colspan="9" class="text-center">Sipariş bulunamadı.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Mobil kartlar --}}
        <div class="mobile-cards p-3">
          @forelse($orders as $o)
            @php
              $reserveQty = ($o->order_type === 'sale' && ! $o->is_paid)
                            ? $o->products->sum('pivot.amount') : 0;
            @endphp
            <div class="card shadow-sm p-3 mb-3">
              <p><strong>Müşteri:</strong> {{ $o->customer->customer_name }}</p>
              <p><strong>Firma:</strong> {{ $o->company->company_name ?? '—' }}</p>
              <p><strong>Tip:</strong> {{ $o->order_type==='sale' ? 'Satış' : 'Alış' }}</p>
              <p><strong>Sipariş:</strong> {{ $o->order_date?->format('d.m.Y') }}</p>
              <p><strong>Teslim:</strong> {{ $o->delivery_date?->format('d.m.Y') ?? '—' }}</p>
              <p><strong>Ödeme:</strong> {{ $o->is_paid ? 'Ödendi' : 'Bekliyor' }}</p>
              <p><strong>Rezerve:</strong> {{ $reserveQty ?: '—' }}</p>
              <p><strong>Toplam:</strong> {{ number_format($o->total_amount,2) }} ₺</p>
              <div class="mt-2">
                <a href="{{ route('orders.show',$o) }}" class="btn btn-sm btn-info flex-fill">Göster</a>

                @if($o->is_paid)
                  <button class="btn btn-sm btn-secondary flex-fill" disabled>Düzenle</button>
                  <button class="btn btn-sm btn-secondary flex-fill" disabled>Sil</button>
                @else
                  <a href="{{ route('orders.edit',$o) }}" class="btn btn-sm btn-warning flex-fill">Düzenle</a>
                  <form action="{{ route('orders.destroy',$o) }}" method="POST" class="d-inline flex-fill">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Silinsin mi?')" class="btn btn-sm btn-danger w-100">Sil</button>
                  </form>
                @endif
              </div>
            </div>
          @empty
            <p class="text-center text-muted">Sipariş bulunamadı.</p>
          @endforelse
        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
.mobile-cards{display:none}
@media(max-width:768px){.desktop-table{display:none}.mobile-cards{display:block}}
.table td,.table th{white-space:normal!important;word-break:break-word}
button[disabled]{opacity:.5;cursor:not-allowed}
</style>
@endpush
