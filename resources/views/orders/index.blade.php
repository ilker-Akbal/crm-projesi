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
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>Müşteri</th>
              <th>Firma</th>
              <th>Tip</th>          {{-- sale | purchase --}}
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

                {{-- ▼ Tip (rozet) --}}
                <td>
                  @if($o->order_type === 'sale')
                    <span class="badge badge-info">Satış</span>
                  @else
                    <span class="badge badge-secondary">Alış</span>
                  @endif
                </td>

                <td>{{ $o->order_date?->format('d.m.Y') }}</td>
                <td>{{ $o->delivery_date?->format('d.m.Y') ?? '—' }}</td>

                {{-- ▼ Ödeme durumu --}}
                <td>
                  @if($o->is_paid)
                    <span class="badge badge-success">
                      Ödendi {{ $o->paid_at?->format('d.m.Y') }}
                    </span>
                  @else
                    <span class="badge badge-warning">Bekliyor</span>
                  @endif
                </td>

                {{-- ▼ Rezerve miktar (sadece bekleyen satış) --}}
                <td class="text-end">
                  {{ $reserveQty ?: '—' }}
                </td>

                <td class="text-end">{{ number_format($o->total_amount, 2) }}</td>

                <td>
                  <a href="{{ route('orders.show', $o) }}" class="btn btn-sm btn-info">Göster</a>
                  <a href="{{ route('orders.edit', $o) }}" class="btn btn-sm btn-warning">Düzenle</a>
                  <form action="{{ route('orders.destroy', $o) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"
                            onclick="return confirm('Silinsin mi?')">Sil</button>
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
    </div>
  </div>
</section>
@endsection
