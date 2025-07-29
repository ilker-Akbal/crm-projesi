@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">

      {{-- Başlık + “Yeni” --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Ürünler</h3>
        <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">Ürün Ekle</a>
      </div>

      <div class="card-body p-0">

        {{-- Masaüstü için tablo --}}
        <div class="desktop-table table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Ürün Adı</th>
                <th>Müşteri</th>
                <th class="text-end">Toplam</th>
                <th class="text-end">Bloke</th>
                <th class="text-end">Rezerve</th>
                <th class="text-end">Kullanılabilir</th>
                <th class="text-end">Son Fiyat</th>
                <th class="text-end">İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($products as $prod)
                @php
                  $lastStock = $prod->stocks->sortByDesc('id')->first();
                @endphp
                <tr>
                  <td>{{ $prod->product_name }}</td>
                  <td>{{ $prod->customer->customer_name ?? '—' }}</td>
                  <td class="text-end">{{ $lastStock?->stock_quantity ?? 0 }}</td>
                  <td class="text-end">{{ $lastStock?->blocked_stock ?? 0 }}</td>
                  <td class="text-end">{{ $lastStock?->reserved_stock ?? 0 }}</td>
                  <td class="text-end">{{ $lastStock?->available_stock ?? 0 }}</td>
                  <td class="text-end">{{ number_format($prod->latest_price, 2) }} ₺</td>
                  <td class="text-end">
                    <a href="{{ route('products.show', $prod) }}" class="btn btn-sm btn-info">Görüntüle</a>
                    <a href="{{ route('products.edit', $prod) }}" class="btn btn-sm btn-warning">Düzenle</a>
                    <form action="{{ route('products.destroy', $prod) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?')" class="btn btn-sm btn-danger">Sil</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="8" class="text-center">Ürün bulunamadı.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Mobil için kart görünümü --}}
        <div class="mobile-cards p-3">
          @forelse($products as $prod)
            @php
              $lastStock = $prod->stocks->sortByDesc('id')->first();
            @endphp
            <div class="card shadow-sm p-3 mb-3">
              <p><strong>Ürün Adı:</strong> {{ $prod->product_name }}</p>
              <p><strong>Müşteri:</strong> {{ $prod->customer->customer_name ?? '—' }}</p>
              <p><strong>Toplam:</strong> {{ $lastStock?->stock_quantity ?? 0 }}</p>
              <p><strong>Bloke:</strong> {{ $lastStock?->blocked_stock ?? 0 }}</p>
              <p><strong>Rezerve:</strong> {{ $lastStock?->reserved_stock ?? 0 }}</p>
              <p><strong>Kullanılabilir:</strong> {{ $lastStock?->available_stock ?? 0 }}</p>
              <p><strong>Son Fiyat:</strong> {{ number_format($prod->latest_price, 2) }} ₺</p>
              <div class="mt-2">
                <a href="{{ route('products.show', $prod) }}" class="btn btn-sm btn-info">Görüntüle</a>
                <a href="{{ route('products.edit', $prod) }}" class="btn btn-sm btn-warning">Düzenle</a>
                <form action="{{ route('products.destroy', $prod) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?')" class="btn btn-sm btn-danger">Sil</button>
                </form>
              </div>
            </div>
          @empty
            <p class="text-center text-muted">Ürün bulunamadı.</p>
          @endforelse
        </div>

      </div>

      @if(method_exists($products, 'links'))
        <div class="card-footer">
          {{ $products->links() }}
        </div>
      @endif
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  /* Masaüstü ve mobil görünüm ayırımı */
  .mobile-cards { display: none; }
  @media (max-width: 768px) {
    .desktop-table { display: none; }
    .mobile-cards { display: block; }
  }

  /* Satır kırma */
  .table td, .table th {
    white-space: normal !important;
    word-break: break-word;
  }
</style>
@endpush
