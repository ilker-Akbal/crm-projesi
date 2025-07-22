@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Ürün Fiyatları</h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Ürün</th>
              <th>Fiyat</th>
              <th>Güncelleme Tarihi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($productPrices as $price)
            <tr>
              <td>{{ $price->id }}</td>
              <td>{{ $price->product->product_name }}</td>
              <td>{{ number_format($price->price,2) }}</td>
              <td>{{ $price->updated_at }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="text-center">Fiyat kaydı bulunamadı.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
