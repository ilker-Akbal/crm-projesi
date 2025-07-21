@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit Product #{{ $product->id }}</h3>
      </div>
      <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
          @include('partials.alerts')

          <div class="form-group">
            <label for="product_name">Product Name *</label>
            <input type="text" name="product_name" id="product_name" class="form-control"
                   value="{{ old('product_name', $product->product_name) }}" required>
          </div>

          <div class="form-group">
            <label for="explanation">Explanation</label>
            <textarea name="explanation" id="explanation" rows="3" class="form-control">{{ old('explanation', $product->explanation) }}</textarea>
          </div>

          <div class="form-group">
            <label for="price">Price (leave blank if no change)</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control"
                   placeholder="Current: {{ $product->prices->last()->price ?? 'N/A' }}">
          </div>

          <div class="form-group">
            <label for="stock_quantity">Stock Quantity (leave blank if no change)</label>
            <input type="number" name="stock_quantity" id="stock_quantity" class="form-control"
                   placeholder="Current: {{ $product->stocks->last()->stock_quantity ?? 'N/A' }}">
          </div>

        </div>
        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('products.index') }}" class="btn btn-secondary mr-2">Cancel</a>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
