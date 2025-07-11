@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-header"><h3 class="card-title">Stok Girişi</h3></div>

      <form action="{{ route('product_stocks.store') }}" method="POST">
        @csrf
        <div class="card-body">
          @include('partials.alerts')

          <div class="form-group">
            <label for="product_id">Ürün</label>
            <select name="product_id" id="product_id" class="form-control" required>
              <option value="">-- seçiniz --</option>
              @foreach ($products as $p)
                <option value="{{ $p->id }}" {{ old('product_id')==$p->id?'selected':'' }}>
                  {{ $p->product_name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="stock_quantity">Stok Miktarı</label>
            <input type="number" name="stock_quantity" id="stock_quantity"
                   class="form-control" min="0" value="{{ old('stock_quantity') }}" required>
          </div>

          <div class="form-group">
            <label for="update_date">Güncelleme Tarihi</label>
            <input type="date" name="update_date" id="update_date"
                   class="form-control" value="{{ old('update_date', today()->toDateString()) }}">
          </div>
        </div>

        <div class="card-footer">
          @include('partials.form-buttons')
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
