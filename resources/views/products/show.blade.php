@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Ürün #{{ $product->id }} Detayları</h2>
  <ul class="list-group mb-3">
    <li class="list-group-item"><strong>Ad:</strong> {{ $product->Product_name }}</li>
    <li class="list-group-item"><strong>Müşteri:</strong> {{ $product->customer?->customer_name }}</li>
    <li class="list-group-item"><strong>Açıklama:</strong> {{ $product->explanation }}</li>
    <li class="list-group-item"><strong>Oluşturulma Tarihi:</strong> {{ $product->created_at }}</li>
    <li class="list-group-item"><strong>Güncelleme Tarihi:</strong> {{ $product->updated_at }}</li>
  </ul>

  <h4>Stoklar</h4>
  <table class="table table-bordered mb-4">
    <thead>
      <tr>
        <th>Tarih</th>
        <th>Miktar</th>
      </tr>
    </thead>
    <tbody>
      @forelse($product->stocks as $s)
      <tr>
        <td>{{ $s->update_date }}</td>
        <td>{{ $s->stock_quantity }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="2" class="text-center">Stok kaydı bulunamadı.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  <h4>Fiyatlar</h4>
  <table class="table table-bordered mb-4">
    <thead>
      <tr>
        <th>Tarih</th>
        <th>Fiyat</th>
      </tr>
    </thead>
    <tbody>
      @forelse($product->prices as $p)
      <tr>
        <td>{{ $p->updated_at }}</td>
        <td>{{ number_format($p->price,2) }}</td>
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
