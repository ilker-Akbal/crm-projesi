@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Account #{{ $account->id }}</h2>
  <ul class="list-group mb-3">
  <li class="list-group-item"><strong>Customer:</strong> {{ $account->customer->customer_name }}</li>

  {{-- Yeni satırlar --}}
  <li class="list-group-item">
      <strong>Opening Balance:</strong>
      {{ number_format($opening, 2) }}
  </li>
  <li class="list-group-item">
      <strong>Current Balance:</strong>
      {{ number_format($closing, 2) }}
  </li>

  <li class="list-group-item"><strong>Opening Date:</strong> {{ $account->opening_date }}</li>
  <li class="list-group-item"><strong>Created At:</strong> {{ $account->created_at }}</li>
  <li class="list-group-item"><strong>Updated At:</strong> {{ $account->updated_at }}</li>
</ul>
  <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
