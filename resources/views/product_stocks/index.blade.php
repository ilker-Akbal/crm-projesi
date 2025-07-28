

@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">

    {{-- Başlık --}}
    <div class="card card-outline card-primary mb-3">
      <div class="card-header d-flex justify-content-between">
        <h3 class="card-title mb-0">Ürün Stokları</h3>
        <div class="card-tools">
          <a href="{{ route('product_serials.index') }}" class="btn btn-sm btn-primary mr-2">
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
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Başlangıç">
          </div>
          <div class="col-md-3">
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="Bitiş">
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filtrele</button>
            <a href="{{ route('product_stocks.index') }}" class="btn btn-secondary">Temizle</a>
          </div>
        </form>
      </div>
    </div>

    {{-- Tablo --}}
    <div class="card card-outline card-primary">
      <div class="card-body p-0">
        @php $stocksArr = $productStocks->values(); @endphp
        <table class="table table-hover mb-0">
          <thead class="text-center">
            <tr>
              <th>Ürün</th>
              <th class="text-end">Toplam</th>
              <th class="text-end">Bloke</th>
              <th class="text-end">Rezerve</th>
              <th>Kullanılabilir</th>
              <th>Güncelleme</th>
              <th>Hareket Türü</th>
              <th class="text-end">Miktar</th>
            </tr>
          </thead>
          <tbody>
            @forelse($stocksArr as $s)
              @php
                $prev = $stocksArr->get($loop->index + 1);
                $prevQty = $prev?->stock_quantity ?? 0;
                $diff = $s->stock_quantity - $prevQty;
                $type = $diff >= 0 ? 'Giriş' : 'Çıkış';
                $qty  = abs($diff);
                $avail = $s->available_stock;
                $badge = $avail == 0   ? 'danger'
                       : ($avail < 10  ? 'warning'
                       :  'success');
              @endphp
              <tr class="text-center">
                <td class="text-start">{{ $s->product->product_name }}</td>
                <td class="text-end">{{ $s->stock_quantity }}</td>
                <td class="text-end">{{ $s->blocked_stock }}</td>
                <td class="text-end">{{ $s->reserved_stock }}</td>
                <td>
                  <span class="badge badge-{{ $badge }}">{{ $avail }}</span>
                </td>
                <td>{{ $s->update_date->format('d.m.Y') }}</td>
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
