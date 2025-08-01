{{-- resources/views/movements/show.blade.php (Hesap satırı kaldırıldı) --}}
@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Hareket #{{ $movement->id }}</h2>
  <ul class="list-group mb-3">
    <li class="list-group-item"><strong>Tarih:</strong> {{ $movement->departure_date }}</li>
    <li class="list-group-item"><strong>Tutar:</strong> {{ number_format($movement->amount,2) }}</li>
    <li class="list-group-item"><strong>Tür:</strong> {{ $movement->movement_type === 'Credit' ? 'Alacak' : ($movement->movement_type === 'Debit' ? 'Borç' : $movement->movement_type) }}</li>
    <li class="list-group-item"><strong>Açıklama:</strong> {{ $movement->explanation }}</li>
  </ul>
  <a href="{{ route('movements.index') }}" class="btn btn-secondary">Listeye Dön</a>
</div>
@endsection