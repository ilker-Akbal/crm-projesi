@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">

    {{-- -------- Başlık -------- --}}
    <div class="card card-outline card-primary mb-3">
      <div class="card-header d-flex justify-content-between">
        <h3 class="card-title mb-0">Ürün Stokları</h3>

        <div class="card-tools">
          <a href="{{ route('product_serials.index') }}"
             class="btn btn-sm btn-primary mr-2">
            Seri Numaraları
          </a>
          <a href="{{ route('product_serials.create') }}"
             class="btn btn-sm btn-success mr-2">
            Seri Numarası Ekle
          </a>
          <a href="{{ route('product_stocks.create') }}"
             class="btn btn-sm btn-info">
            Stok Ekle
          </a>
        </div>
      </div>
    </div>

    {{-- -------- Tablo -------- --}}
    <div class="card card-outline card-primary">
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead class="text-center">
            <tr>
              <th>Ürün</th>
              <th class="text-end">Toplam</th>
              <th class="text-end">Bloke</th>
              <th class="text-end">Rezerve</th>
              <th>Kullanılabilir</th>
              <th>Güncelleme</th>
            </tr>
          </thead>
          <tbody>
            @forelse($productStocks as $s)
              @php
                $avail = $s->available_stock;
                $badge = $avail == 0   ? 'danger'
                       : ($avail < 10  ? 'warning'
                       :  'success');
              @endphp
              <tr>
                <td>{{ $s->product->product_name }}</td>

                <td class="text-end">{{ $s->stock_quantity }}</td>
                <td class="text-end">{{ $s->blocked_stock }}</td>
                <td class="text-end">{{ $s->reserved_stock }}</td>

                {{-- Kullanılabilir rozet --}}
                <td class="text-center">
                  <span class="badge badge-{{ $badge }}">{{ $avail }}</span>
                </td>

                <td class="text-center">{{ $s->update_date->format('d.m.Y') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">Kayıt yok</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>
@endsection
