@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">

    {{-- Başlık --}}
    <div class="card card-outline card-primary mb-3">
      <div class="card-header d-flex justify-content-between">
        <h3 class="card-title mb-0">Ürün Stokları</h3>
        <div class="card-tools">
          <a href="{{ route('product_serials.index') }}" class="btn btn-sm btn-primary">
            Seri Numaraları
          </a>
        </div>
      </div>
    </div>

    {{-- Filtre Formu --}}
    <div class="card card-outline card-secondary mb-3">
      <div class="card-body">
        <form method="GET" action="{{ route('product_stocks.index') }}" class="row g-2">
          <div class="col-md-3">
            <select name="product_id" class="form-control">
              <option value="">Tüm Ürünler</option>
              @foreach($products as $prod)
                <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                  {{ $prod->product_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
          </div>
          <div class="col-md-3">
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
          </div>
          <div class="col-md-3 d-flex">
            <button type="submit" class="btn btn-primary me-2">Filtrele</button>
            <a href="{{ route('product_stocks.index') }}" class="btn btn-secondary">Temizle</a>
          </div>
        </form>
      </div>
    </div>

    {{-- Tablo --}}
    <div class="card card-outline card-primary">
      <div class="card-body p-0">
        @php
          // Tarihe göre artan sırada işlemleri alalım
          $movements = $productStocks->sortBy('update_date')->values();
        @endphp
        <table class="table table-hover mb-0">
          <thead class="text-center">
            <tr>
              <th>Ürün</th>
              <th class="text-end">Toplam</th>
              <th class="text-end">Bloke</th>
              <th class="text-end">Rezerve</th>
              <th>Kullanılabilir</th>
              <th>Güncelleme</th>
              <th>Hareket</th>
              <th class="text-end">Miktar</th>
            </tr>
          </thead>
          <tbody>
            @forelse($movements as $idx => $m)
              @php
                // Bir önceki kayda bakıp farkı alıyoruz
                $prevQty = $movements[$idx - 1]->stock_quantity ?? 0;
                $delta   = $m->stock_quantity - $prevQty;
                $type    = $delta >= 0 ? 'Giriş' : 'Çıkış';
                $qty     = abs($delta);
                $avail   = $m->available_stock;
                $badge   = $avail == 0   ? 'danger'
                         : ($avail < 10  ? 'warning'
                         :  'success');
              @endphp
              <tr class="text-center">
                <td class="text-start">{{ $m->product->product_name }}</td>
                <td class="text-end">{{ $m->stock_quantity }}</td>
                <td class="text-end">{{ $m->blocked_stock }}</td>
                <td class="text-end">{{ $m->reserved_stock }}</td>
                <td>
                  <span class="badge badge-{{ $badge }}">{{ $avail }}</span>
                </td>
                <td>{{ $m->update_date->format('d.m.Y') }}</td>
                <td>{{ $type }}</td>
                <td class="text-end">{{ $qty }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center">Kayıt yok</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>
@endsection
