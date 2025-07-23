@extends('layouts.app')
@section('content')
<section class="content"><div class="container-fluid">
  <div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between">
      <h3 class="card-title">Ürün Stokları</h3>
      <div class="card-tools">
        <a href="{{ route('product_serials.index') }}"
           class="btn btn-sm btn-primary mr-2">
          Seri Numaraları
        </a>
        <a href="{{ route('product_serials.create') }}"
           class="btn btn-sm btn-success">
          Seri Numarası Ekle
        </a>
      </div>
    </div>
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>ID</th><th>Ürün</th>
            <th>Toplam</th><th>Bloke</th>
            <th>Rezerve</th><th>Kullanılabilir</th>
            <th>Güncelleme</th>
          </tr>
        </thead>
        <tbody>
          @forelse($productStocks as $s)
          <tr>
            <td>{{ $s->id }}</td>
            <td>{{ $s->product->product_name }}</td>
            <td>{{ $s->stock_quantity }}</td>
            <td>{{ $s->blocked_stock }}</td>
            <td>{{ $s->reserved_stock }}</td>
            <td>{{ $s->available_stock }}</td>
            <td>{{ $s->update_date }}</td>
          </tr>
          @empty
          <tr><td colspan="7" class="text-center">Kayıt yok</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div></section>
@endsection
