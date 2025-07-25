@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Ürün #{{ $product->id }} Detayları</h2>

  {{-- -------- Temel Bilgiler -------- --}}
  <ul class="list-group mb-3">
    <li class="list-group-item"><strong>Ürün Adı:</strong> {{ $product->product_name }}</li>
    <li class="list-group-item"><strong>Müşteri:</strong> {{ $product->customer?->customer_name ?? '—' }}</li>
    <li class="list-group-item"><strong>Açıklama:</strong> {{ $product->explanation ?? '—' }}</li>
    <li class="list-group-item"><strong>Oluşturulma:</strong> {{ $product->created_at->format('d.m.Y H:i') }}</li>
    <li class="list-group-item"><strong>Güncelleme:</strong> {{ $product->updated_at->format('d.m.Y H:i') }}</li>
  </ul>

  {{-- -------- Stok Geçmişi -------- --}}
  <h4>Stok Kayıtları</h4>
  <table class="table table-bordered mb-4">
    <thead>
      <tr class="text-center">
        <th>Tarih</th>
        <th>Toplam</th>
        <th>Bloke</th>
        <th>Rezerve</th>
        <th>Kullanılabilir</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($product->stocks->sortByDesc('update_date') as $s)
        <tr class="text-end">
          <td class="text-center">{{ $s->update_date->format('d.m.Y') }}</td>
          <td>{{ $s->stock_quantity }}</td>
          <td>{{ $s->blocked_stock }}</td>
          <td>{{ $s->reserved_stock }}</td>
          <td>{{ $s->available_stock }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center">Stok kaydı bulunamadı.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{-- -------- Seri numaraları -------- --}}
  <h4>Seri Numaraları</h4>
@if ($product->serials->isEmpty())
    <p class="text-muted">Seri numarası kaydı bulunamadı.</p>
@else
    @php  /* durum → badge rengi */
        $badgeMap = [
            'available' => 'secondary',
            'blocked'   => 'dark',
            'reserved'  => 'warning',
            'sold'      => 'success',
        ];
    @endphp

    <ul class="list-group mb-4">
        @foreach ($product->serials as $sn)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $sn->serial_number }}
                <span class="badge badge-{{ $badgeMap[$sn->status] ?? 'secondary' }}">
                    {{ ucfirst($sn->status) }}
                </span>
            </li>
        @endforeach
    </ul>
@endif

  {{-- -------- Fiyatlar -------- --}}
  <h4>Fiyat Geçmişi</h4>
  <table class="table table-bordered mb-4">
    <thead>
      <tr class="text-center">
        <th>Tarih</th>
        <th>Fiyat (₺)</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($product->prices->sortByDesc('updated_at') as $p)
        <tr class="text-end">
          <td class="text-center">{{ $p->updated_at->format('d.m.Y') }}</td>
          <td>{{ number_format($p->price, 2) }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="2" class="text-center">Fiyat kaydı bulunamadı.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <a href="{{ route('products.index') }}" class="btn btn-secondary">Listeye Dön</a>
</div>
@endsection
