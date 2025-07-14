@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Offer #{{ $offer->id }} Details</h2>
  <ul class="list-group">
    <li class="list-group-item"><strong>Customer:</strong> {{ $offer->customer->customer_name }}</li>
    <li class="list-group-item"><strong>Order #:</strong> {{ $offer->order_id? '#'.$offer->order_id:'-' }}</li>
    <li class="list-group-item"><strong>Offer Date:</strong> {{ $offer->offer_date }}</li>
    <li class="list-group-item"><strong>Valid Until:</strong> {{ $offer->valid_until }}</li>
    <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($offer->status) }}</li>
    <li class="list-group-item"><strong>Total:</strong> {{ number_format($offer->total_amount,2) }}</li>
  </ul>
  <a href="{{ route('offers.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>
@endsection
