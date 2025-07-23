@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Sipariş #{{ $order->id }} Detayları</h2>
  <ul class="list-group">
    <li class="list-group-item"><strong>Müşteri:</strong> {{ $order->customer->customer_name }}</li>
    <li class="list-group-item"><strong>Sipariş Tarihi:</strong> {{ $order->order_date->format('Y-m-d') }}</li>
    <li class="list-group-item"><strong>Teslim Tarihi:</strong> {{ $order->delivery_date?->format('Y-m-d') ?? '-' }}</li>
    <li class="list-group-item"><strong>Toplam:</strong> {{ number_format($order->total_amount,2) }} ₺</li>
  </ul>

  <h4 class="mt-4">Ürünler</h4>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Ürün</th>
        <th>Miktar</th>
        <th>Birim Fiyat</th>
        <th>Ara Toplam</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->orderProducts as $line)
      <tr>
        <td>{{ $line->product->product_name }}</td>
        <td>{{ $line->amount }}</td>
        <td>{{ number_format($line->unit_price,2) }} ₺</td>
        <td>{{ number_format($line->amount * $line->unit_price,2) }} ₺</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <a href="{{ route('orders.index') }}" class="btn btn-secondary">Listeye Dön</a>
</div>
@endsection
