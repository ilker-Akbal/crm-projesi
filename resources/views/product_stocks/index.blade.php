@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Ürün Stokları</h3>
        <a href="{{ route('product_stocks.create') }}" class="btn btn-sm btn-primary">Stok Girişi</a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Ürün</th>
                <th>Stok Miktarı</th>
                <th>Güncelleme Tarihi</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($productStocks as $s)
                <tr>
                  <td>{{ $s->id }}</td>
                  <td>{{ $s->product?->product_name }}</td>
                  <td>{{ $s->stock_quantity }}</td>
                  <td>{{ $s->update_date }}</td>
                  <td>
                    <a href="{{ route('product_stocks.edit', $s) }}" class="btn btn-sm btn-warning">Düzenle</a>
                    <form action="{{ route('product_stocks.destroy', $s) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" onclick="return confirm('Silinsin mi?')">Sil</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center">Kayıt bulunamadı</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
