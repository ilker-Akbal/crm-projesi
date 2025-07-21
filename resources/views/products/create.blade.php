@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Add Product</h3></div>
      <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="card-body">

          {{-- Ürün bilgileri --}}
          <div class="form-group">
            <label for="product_name">Product Name</label>
            <input  type="text" name="product_name" id="product_name"
                    class="form-control" value="{{ old('product_name') }}" required>
          </div>

          

          <div class="form-group">
            <label for="explanation">Explanation</label>
            <textarea name="explanation" id="explanation"
                      class="form-control" rows="3">{{ old('explanation') }}</textarea>
          </div>

          <hr>

          {{-- İlk stok --}}
          <div class="form-row">
            <div class="col-md-6">
              <label for="stock_quantity">Initial Stock Quantity</label>
              <input type="number" name="stock_quantity" id="stock_quantity"
                     class="form-control" min="0" value="{{ old('stock_quantity', 0) }}" required>
            </div>

            {{-- İlk fiyat --}}
            <div class="col-md-6">
              <label for="price">Initial Price (₺)</label>
              <input type="number" name="price" id="price" step="0.01"
                     class="form-control" min="0" value="{{ old('price', 0) }}" required>
            </div>
          </div>

        </div>
        <div class="card-footer d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
