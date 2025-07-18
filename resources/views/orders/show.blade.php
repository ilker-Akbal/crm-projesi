@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Order #{{ $order->id }} Details</h2>
  <ul class="list-group">
    <li class="list-group-item"><strong>Customer:</strong> {{ $order->customer->customer_name }}</li>
    <li class="list-group-item"><strong>Order Date:</strong> {{ $order->order_date }}</li>
    <li class="list-group-item"><strong>Delivery Date:</strong> {{ $order->delivery_date }}</li>
    <li class="list-group-item"><strong>Total:</strong> {{ number_format($order->total_amount,2) }}</li>
  </ul>

  <h4 class="mt-4">Items</h4>
  <table class="table table-bordered">
    <thead>
      <tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
    </thead>
    <tbody>
      @foreach($order->products as $p)
      <tr>
        <td>{{ $p->product_name }}</td>
        <td>{{ $p->pivot->amount }}</td>
        <td>{{ number_format($p->pivot->unit_price,2) }}</td>
        <td>{{ number_format($p->pivot->amount * $p->pivot->unit_price,2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
