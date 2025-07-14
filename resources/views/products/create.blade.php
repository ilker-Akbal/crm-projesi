@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Add Product</h3></div>
      <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="card-body">


          <div class="form-group">
            <label for="product_name">Product Name *</label>
            <input type="text" name="product_name" id="product_name"
                   class="form-control" value="{{ old('product_name') }}" required>
          </div>

          <div class="form-group">
            <label for="customer_id">Customer (optional)</label>
            <select name="customer_id" id="customer_id" class="form-control">
              <option value="">-- select --</option>
              @foreach($customers as $c)
                <option value="{{ $c->id }}"
                  {{ old('customer_id')==$c->id?'selected':'' }}>
                  {{ $c->customer_name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="explanation">Explanation</label>
            <textarea name="explanation" id="explanation" rows="3"
              class="form-control">{{ old('explanation') }}</textarea>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('products.index') }}" class="btn btn-secondary mr-2">Cancel</a>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
