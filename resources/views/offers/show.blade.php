@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Teklif #{{ $offer->id }} Detayları</h2>
  <ul class="list-group">
    <li class="list-group-item"><strong>Müşteri:</strong> {{ $offer->customer->customer_name }}</li>
    <li class="list-group-item"><strong>Sipariş #:</strong> {{ $offer->order_id? '#'.$offer->order_id:'-' }}</li>
    <li class="list-group-item"><strong>Teklif Tarihi:</strong> {{ $offer->offer_date }}</li>
    <li class="list-group-item"><strong>Geçerlilik Tarihi:</strong> {{ $offer->valid_until }}</li>
    <li class="list-group-item"><strong>Durum:</strong> {{ ucfirst($offer->status) }}</li>
    <li class="list-group-item"><strong>Toplam:</strong> {{ number_format($offer->total_amount,2) }}</li>
  </ul>
  <a href="{{ route('offers.index') }}" class="btn btn-secondary mt-3">Listeye Dön</a>
</div>
@endsection
