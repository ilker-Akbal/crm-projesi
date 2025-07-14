@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Company #{{ $company->id }} Details</h2>
  <ul class="list-group">
    <li class="list-group-item"><strong>Name:</strong> {{ $company->company_name }}</li>
    <li class="list-group-item"><strong>Tax #:</strong> {{ $company->tax_number }}</li>
    <li class="list-group-item"><strong>Phone:</strong> {{ $company->phone_number }}</li>
    <li class="list-group-item"><strong>Email:</strong> {{ $company->Email }}</li>
    <li class="list-group-item"><strong>Address:</strong> {{ $company->Adress }}</li>
    <li class="list-group-item"><strong>Registration Date:</strong> {{ $company->registration_date }}</li>
    <li class="list-group-item"><strong>Current Role:</strong> {{ $company->current_role }}</li>
    <li class="list-group-item"><strong>Customer:</strong> {{ $company->customer?->customer_name }}</li>
  </ul>
  <a href="{{ route('companies.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>
@endsection
