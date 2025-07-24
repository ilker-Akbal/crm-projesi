@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Firma #{{ $company->id }} Detayları</h2>
  <ul class="list-group">
    <li class="list-group-item"><strong>Adı:</strong> {{ $company->company_name }}</li>
    <li class="list-group-item"><strong>Vergi No:</strong> {{ $company->tax_number }}</li>
    <li class="list-group-item"><strong>Telefon:</strong> {{ $company->phone_number }}</li>
    <li class="list-group-item"><strong>E-posta:</strong> {{ $company->email }}</li>
    <li class="list-group-item"><strong>Adres:</strong> {{ $company->address }}</li>
    <li class="list-group-item">
      <strong>Kayıt Tarihi:</strong> 
      {{ \Carbon\Carbon::parse($company->registration_date)->format('d.m.Y') }}
    </li>
    <li class="list-group-item">
      <strong>Cari Rol:</strong> 
      @switch($company->current_role)
        @case('admin') Yönetici @break
        @case('user') Kullanıcı @break
        @case('customer') Müşteri @break
        @default Belirtilmemiş
      @endswitch
    </li>
    <li class="list-group-item"><strong>Müşteri:</strong> {{ $company->customer?->customer_name ?? 'Müşteri Yok' }}</li>
  </ul>
  <a href="{{ route('companies.index') }}" class="btn btn-secondary mt-3">Listeye Dön</a>
</div>
@endsection
