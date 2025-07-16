@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Customer Details</h2>
    <div class="card p-3">
        <p><strong>ID:</strong> {{ $customer->id }}</p>
        <p><strong>Name:</strong> {{ $customer->customer_name }}</p>
        <p><strong>Type:</strong> {{ $customer->customer_type }}</p>
        <p><strong>Phone:</strong> {{ $customer->phone }}</p>
        <p><strong>Email:</strong> {{ $customer->email }}</p>
        <p><strong>Address:</strong> {{ $customer->address }}</p>
    </div>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>
@endsection
