@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Müşteri Detayları</h2>
    <div class="card p-3">
        <p><strong>ID:</strong> {{ $customer->id }}</p>
        <p><strong>Ad:</strong> {{ $customer->customer_name }}</p>
        <p><strong>Tip:</strong> {{ $customer->customer_type }}</p>
        <p><strong>Telefon:</strong> {{ $customer->phone }}</p>
        <p><strong>E-posta:</strong> {{ $customer->email }}</p>
        <p><strong>Adres:</strong> {{ $customer->address }}</p>
    </div>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary mt-3">Listeye Geri Dön</a>
</div>
@endsection
