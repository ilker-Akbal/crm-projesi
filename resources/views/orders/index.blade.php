@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">

      {{-- Başlık + “Yeni” --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Siparişler</h3>
        <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">Yeni Sipariş Ekle</a>
      </div>

      <div class="card-body p-0">

        {{-- Masaüstü için tablo --}}
        <div class="desktop-table table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Müşteri</th>
                <th>Firma</th>
                <th>Tip</th>
                <th>Sipariş</th>
                <th>Teslim</th>
                <th>Ödeme</th>
                <th class="text-end">Rezerve</th>
                <th class="text-end">Toplam</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $o)
                @php
                  $reserveQty = ($o->order_type === 'sale' && ! $o->is_paid)
                                ? $o->products->sum('pivot.amount')
                                : 0;
                @endphp
                <tr>
                  <td>{{ $o->customer->customer_name }}</td>
                  <td>{{ $o->company->company_name ?? '—' }}</td>

                  {{-- Tip (rozet) --}}
                  <td>
                    @if($o->order_type === 'sale')
                      <span class="badge bg-info text-dark">Satış</span>
                    @else
                      <span class="badge bg-secondary">Alış</span>
                    @endif
                  </td>

                  <td>{{ $o->order_date?->format('d.m.Y') }}</td>
                  <td>{{ $o->delivery_date?->format('d.m.Y') ?? '—' }}</td>

                  {{-- Ödeme durumu --}}
                  <td>
                    @if($o->is_paid)
                      <span class="badge bg-success">
                        Ödendi {{ $o->paid_at?->format('d.m.Y') }}
                      </span>
                    @else
                      <span class="badge bg-warning text-dark">Bekliyor</span>
                    @endif
                  </td>

                  <td class="text-end">{{ $reserveQty ?: '—' }}</td>
                  <td class="text-end">{{ number_format($o->total_amount, 2) }}</td>

                  <td>
                    <a href="{{ route('orders.show', $o) }}" class="btn btn-sm btn-info">Göster</a>
                    <a href="{{ route('orders.edit', $o) }}" class="btn btn-sm btn-warning">Düzenle</a>
                    <form action="{{ route('orders.destroy', $o) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" onclick="return confirm('Silinsin mi?')">Sil</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center">Herhangi bir sipariş bulunamadı.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Mobil için kart görünümü --}}
        <div class="mobile-cards p-3">
          @forelse($orders as $o)
            @php
              $reserveQty = ($o->order_type === 'sale' && ! $o->is_paid)
                            ? $o->products->sum('pivot.amount')
                            : 0;
            @endphp
            <div class="card shadow-sm p-3 mb-3">
              <p><strong>Müşteri:</strong> {{ $o->customer->customer_name }}</p>
              <p><strong>Firma:</strong> {{ $o->company->company_name ?? '—' }}</p>
              <p><strong>Tip:</strong> 
                @if($o->order_type === 'sale') Satış @else Alış @endif
              </p>
              <p><strong>Sipariş Tarihi:</strong> {{ $o->order_date?->format('d.m.Y') }}</p>
              <p><strong>Teslim Tarihi:</strong> {{ $o->delivery_date?->format('d.m.Y') ?? '—' }}</p>
              <p><strong>Ödeme:</strong> 
                @if($o->is_paid) 
                  Ödendi ({{ $o->paid_at?->format('d.m.Y') }})
                @else 
                  Bekliyor
                @endif
              </p>
              <p><strong>Rezerve:</strong> {{ $reserveQty ?: '—' }}</p>
              <p><strong>Toplam:</strong> {{ number_format($o->total_amount, 2) }} ₺</p>
              <div class="mt-2">
                <a href="{{ route('orders.show',$o) }}" class="btn btn-sm btn-info">Göster</a>
                <a href="{{ route('orders.edit',$o) }}" class="btn btn-sm btn-warning">Düzenle</a>
                <form action="{{ route('orders.destroy',$o) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button onclick="return confirm('Silinsin mi?')" class="btn btn-sm btn-danger">Sil</button>
                </form>
              </div>
            </div>
          @empty
            <p class="text-center text-muted">Herhangi bir sipariş bulunamadı.</p>
          @endforelse
        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  /* Masaüstü ve mobil görünümü ayır */
  .mobile-cards { display: none; }
  @media (max-width: 768px) {
    .desktop-table { display: none; }
    .mobile-cards { display: block; }
  }

  /* Uzun metinlerin satır kırması */
  .table td, .table th {
    white-space: normal !important;
    word-break: break-word;
  }
</style>
@endpush
