@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      {{-- Başlık + “Yeni” düğmesi --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Ürünler</h3>
        <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">Ürün Ekle</a>
      </div>

      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Ürün Adı</th>
              <th>Müşteri</th>
              <th>Stok</th>
              <th>Son&nbsp;Fiyat</th>
              <th>Güncelleme&nbsp;Tarihi</th>
              <th class="text-right">İşlemler</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($products as $prod)
              <tr>
                <td>{{ $prod->id }}</td>
                <td>{{ $prod->product_name }}</td>
                {{-- müşteri boşsa tire göster --}}
                <td>{{ $prod->customer->customer_name ?? '—' }}</td>
                {{-- toplam stok --}}
                <td>{{ $prod->stocks->sum('stock_quantity') }}</td>
                {{-- son fiyat --}}
                @php
                  $lastPrice = $prod->prices->sortByDesc('created_at')->first();
                @endphp
                <td>{{ $lastPrice ? number_format($lastPrice->price, 2) : '—' }}</td>
                <td>{{ $prod->updated_at->format('d.m.Y H:i') }}</td>
                <td class="text-right">
                  <a href="{{ route('products.show',  $prod) }}" class="btn btn-sm btn-info">Görüntüle</a>
                  <a href="{{ route('products.edit',  $prod) }}" class="btn btn-sm btn-warning">Düzenle</a>
                  <form action="{{ route('products.destroy', $prod) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"
                            onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?')">
                      Sil
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center">Ürün bulunamadı.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- sayfalama varsa --}}
      @if(method_exists($products, 'links'))
        <div class="card-footer">
          {{ $products->links() }}
        </div>
      @endif
    </div>
  </div>
</section>
@endsection
