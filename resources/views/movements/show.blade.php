@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Movement #{{ $movement->id }}</h2>
  <ul class="list-group mb-3">
    <li class="list-group-item"><strong>Account:</strong> {{ $movement->currentCard->customer->customer_name }} ({{ $movement->currentCard->id }})</li>
    <li class="list-group-item"><strong>Date:</strong> {{ $movement->departure_date }}</li>
    <li class="list-group-item"><strong>Amount:</strong> {{ number_format($movement->amount,2) }}</li>
    <li class="list-group-item"><strong>Type:</strong> {{ $movement->movement_type }}</li>
    <li class="list-group-item"><strong>Explanation:</strong> {{ $movement->explanation }}</li>
  </ul>
  <a href="{{ route('movements.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
