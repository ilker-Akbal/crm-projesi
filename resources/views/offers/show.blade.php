{{-- resources/views/offers/show.blade.php (veya ilgili dosya) --}}
@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Teklif #{{ $offer->id }} Detayları</h2>
  <ul class="list-group">
    <li class="list-group-item">
      <strong>Şirket:</strong> {{ $offer->company?->company_name ?? '-' }}
    </li>
    <li class="list-group-item">
      <strong>Teklif Tarihi:</strong> {{ \Carbon\Carbon::parse($offer->offer_date)->format('d.m.Y') }}
    </li>
    <li class="list-group-item">
      <strong>Geçerlilik Tarihi:</strong> {{ $offer->valid_until ? \Carbon\Carbon::parse($offer->valid_until)->format('d.m.Y') : '-' }}
    </li>
    <li class="list-group-item">
      <strong>Durum:</strong> {{ ucfirst($offer->status) }}
    </li>
    <li class="list-group-item">
      <strong>Toplam:</strong> {{ number_format($offer->total_amount,2) }} ₺
    </li>
  </ul>
  <a href="{{ route('offers.index') }}" class="btn btn-secondary mt-3">Listeye Dön</a>
</div>
@endsection
