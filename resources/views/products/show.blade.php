@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Product #{{ $product->id }} Details</h2>
  <ul class="list-group mb-3">
    <li class="list-group-item"><strong>Name:</strong> {{ $product->Product_name }}</li>
    <li class="list-group-item"><strong>Customer:</strong> {{ $product->customer?->customer_name }}</li>
    <li class="list-group-item"><strong>Explanation:</strong> {{ $product->explanation }}</li>
    <li class="list-group-item"><strong>Created At:</strong> {{ $product->created_at }}</li>
    <li class="list-group-item"><strong>Updated At:</strong> {{ $product->updated_at }}</li>
  </ul>

  <h4>Stocks</h4>
  <table class="table table-bordered mb-4">
    <thead><tr><th>Date</th><th>Qty</th></tr></thead>
    <tbody>
      @forelse($product->stocks as $s)
      <tr>
        <td>{{ $s->update_date }}</td>
        <td>{{ $s->stock_quantity }}</td>
      </tr>
      @empty
      <tr><td colspan="2">No stock entries.</td></tr>
      @endforelse
    </tbody>
  </table>

  <h4>Prices</h4>
  <table class="table table-bordered mb-4">
    <thead><tr><th>Date</th><th>Price</th></tr></thead>
    <tbody>
      @forelse($product->prices as $p)
      <tr>
        <td>{{ $p->updated_at }}</td>
        <td>{{ number_format($p->price,2) }}</td>
      </tr>
      @empty
      <tr><td colspan="2">No price entries.</td></tr>
      @endforelse
    </tbody>
  </table>

  <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
