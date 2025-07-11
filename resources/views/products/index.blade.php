@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Ürünler</h3>
        <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">Ürün Ekle</a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Adı</th>
                <th>Müşteri</th>
                <th>Açıklama</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($products as $p)
                <tr>
                  <td>{{ $p->id }}</td>
                  <td>{{ $p->product_name }}</td>
                  <td>{{ $p->customer?->customer_name }}</td>
                  <td>{{ $p->explanation }}</td>
                  <td>
                    <a href="{{ route('products.edit', $p) }}" class="btn btn-sm btn-warning">Düzenle</a>
                    <form action="{{ route('products.destroy', $p) }}" method="POST" class="d-inline">
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
