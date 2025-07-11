@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">

      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Ürün Fiyatları</h3>
        <a href="{{ route('product_prices.create') }}" class="btn btn-sm btn-primary">
          Fiyat Ekle
        </a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Ürün</th>
                <th>Fiyat</th>
                <th>Güncelleme Tarihi</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($productPrices as $price)
                <tr>
                  <td>{{ $price->id }}</td>
                  <td>{{ $price->product?->product_name }}</td>
                  <td>{{ number_format($price->price, 2) }}</td>
                  <td>{{ $price->updated_at }}</td>
                  <td>
                    <a href="{{ route('product_prices.edit', $price) }}" class="btn btn-sm btn-warning">Düzenle</a>
                    <form action="{{ route('product_prices.destroy', $price) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" onclick="return confirm('Silinsin mi?')">Sil</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">Kayıt bulunamadı</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection
