@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Add Product Price</h3></div>
      <form action="{{ route('product_prices.store') }}" method="POST">
        @csrf
        <div class="card-body">
          @include('partials.alerts')

          <div class="form-group">
            <label for="product_id">Product *</label>
            <select name="product_id" id="product_id" class="form-control" required>
              <option value="">-- select --</option>
              @foreach($products as $p)
                <option value="{{ $p->id }}" {{ old('product_id')==$p->id?'selected':'' }}>
                  {{ $p->product_name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="price">Price *</label>
            <input type="number" step="0.01" name="price" id="price"
                   class="form-control" value="{{ old('price') }}" required>
          </div>

          <div class="form-group">
            <label for="updated_at">Update Date *</label>
            <input type="date" name="updated_at" id="updated_at"
                   class="form-control" value="{{ old('updated_at',today()->toDateString()) }}" required>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('product_prices.index') }}" class="btn btn-secondary mr-2">Cancel</a>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
