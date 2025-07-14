@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Add Product Stock</h3></div>
      <form action="{{ route('product_stocks.store') }}" method="POST">
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
            <label for="stock_quantity">Quantity *</label>
            <input type="number" name="stock_quantity" id="stock_quantity"
                   class="form-control" value="{{ old('stock_quantity',0) }}" required>
          </div>

          <div class="form-group">
            <label for="update_date">Update Date *</label>
            <input type="date" name="update_date" id="update_date"
                   class="form-control" value="{{ old('update_date',today()->toDateString()) }}" required>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('product_stocks.index') }}" class="btn btn-secondary mr-2">Cancel</a>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
