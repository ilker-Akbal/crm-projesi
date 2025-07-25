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
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>Ürün Adı</th>
              <th>Müşteri</th>

              {{-- ▼ Yeni stok sütunları --}}
              <th class="text-end">Toplam</th>
              <th class="text-end">Bloke</th>
              <th class="text-end">Rezerve</th>
              <th class="text-end">Kullanılabilir</th>

              <th class="text-end">Son&nbsp;Fiyat</th>
              <th class="text-right">İşlemler</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($products as $prod)
              @php
                /* son stok kaydını çek */
                $lastStock = $prod->stocks->sortByDesc('id')->first();
              @endphp
              <tr>
                <td>{{ $prod->product_name }}</td>
                <td>{{ $prod->customer->customer_name ?? '—' }}</td>

                {{-- ▼ Stok sayıları --}}
                <td class="text-end">{{ $lastStock?->stock_quantity ?? 0 }}</td>
                <td class="text-end">{{ $lastStock?->blocked_stock   ?? 0 }}</td>
                <td class="text-end">{{ $lastStock?->reserved_stock  ?? 0 }}</td>
                <td class="text-end">{{ $lastStock?->available_stock ?? 0 }}</td>

                {{-- Son fiyat (accessor) --}}
                <td class="text-end">
                  {{ number_format($prod->latest_price, 2) }}
                </td>

                <td class="text-right">
                  <a href="{{ route('products.show',  $prod) }}" class="btn btn-sm btn-info">Görüntüle</a>
                  <a href="{{ route('products.edit',  $prod) }}" class="btn btn-sm btn-warning">Düzenle</a>
                  <form action="{{ route('products.destroy', $prod) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button  class="btn btn-sm btn-danger"
                             onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?')">
                      Sil
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center">Ürün bulunamadı.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- sayfalama --}}
      @if(method_exists($products, 'links'))
        <div class="card-footer">
          {{ $products->links() }}
        </div>
      @endif
    </div>
  </div>
</section>
@endsection
