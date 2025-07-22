@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Ürün Düzenle #{{ $product->id }}</h3>
      </div>
      <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
        

          <div class="form-group">
            <label for="product_name">Ürün Adı *</label>
            <input type="text" name="product_name" id="product_name" class="form-control"
                   value="{{ old('product_name', $product->product_name) }}" required>
          </div>

          <div class="form-group">
            <label for="explanation">Açıklama</label>
            <textarea name="explanation" id="explanation" rows="3" class="form-control">{{ old('explanation', $product->explanation) }}</textarea>
          </div>

          <div class="form-group">
            <label for="price">Fiyat (değişiklik yoksa boş bırakın)</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control"
                   placeholder="Mevcut: {{ $product->prices->last()->price ?? 'N/A' }}">
          </div>

          <div class="form-group">
            <label for="stock_quantity">Stok Miktarı (değişiklik yoksa boş bırakın)</label>
            <input type="number" name="stock_quantity" id="stock_quantity" class="form-control"
                   placeholder="Mevcut: {{ $product->stocks->last()->stock_quantity ?? 'N/A' }}">
          </div>

        </div>
        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('products.index') }}" class="btn btn-secondary mr-2">İptal</a>
          <button type="submit" class="btn btn-primary">Güncelle</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
