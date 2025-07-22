@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Hesap #{{ $account->id }}</h2>
  <ul class="list-group mb-3">
    <li class="list-group-item"><strong>Müşteri:</strong> {{ $account->customer->customer_name }}</li>

    {{-- Yeni satırlar --}}
    <li class="list-group-item">
        <strong>Açılış Bakiyesi:</strong>
        {{ number_format($opening, 2) }}
    </li>
    <li class="list-group-item">
        <strong>Güncel Bakiye:</strong>
        {{ number_format($closing, 2) }}
    </li>

    <li class="list-group-item"><strong>Açılış Tarihi:</strong> {{ $account->opening_date }}</li>
    <li class="list-group-item"><strong>Oluşturulma:</strong> {{ $account->created_at }}</li>
    <li class="list-group-item"><strong>Güncellenme:</strong> {{ $account->updated_at }}</li>
  </ul>
  <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Listeye Dön</a>
</div>
@endsection
