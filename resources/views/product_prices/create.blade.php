@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Ürün Fiyatı Ekle</h3></div>
      <form action="{{ route('product_prices.store') }}" method="POST">
        @csrf
        <div class="card-body">

          <div class="form-group">
            <label for="product_id">Ürün *</label>
            <select name="product_id" id="product_id" class="form-control" required>
              <option value="">-- seçiniz --</option>
              @foreach($products as $p)
                <option value="{{ $p->id }}" {{ old('product_id')==$p->id?'selected':'' }}>
                  {{ $p->product_name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="price">Fiyat *</label>
            <input type="number" step="0.01" name="price" id="price"
                   class="form-control" value="{{ old('price') }}" required>
          </div>

          <div class="form-group">
            <label for="updated_at">Güncelleme Tarihi *</label>
            <input type="date" name="updated_at" id="updated_at"
                   class="form-control" value="{{ old('updated_at', today()->toDateString()) }}" required>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('product_prices.index') }}" class="btn btn-secondary mr-2">İptal</a>
          <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
