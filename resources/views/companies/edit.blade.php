{{-- resources/views/companies/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Firma Düzenle')

@section('content')
<div class="container mt-4">
 

  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title">Firma Düzenle #{{ $company->id }}</h3>
    </div>
    <div class="card-body">
      <form action="{{ route('companies.update', $company) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Firma Adı --}}
        <div class="form-group mb-3">
          <label for="company_name">Firma Adı</label>
          <input
            type="text"
            class="form-control @error('company_name') is-invalid @enderror"
            id="company_name"
            name="company_name"
            value="{{ old('company_name', $company->company_name) }}"
            required
          >
          @error('company_name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Vergi Numarası --}}
       <div class="form-group mb-3">
  <label for="tax_number">Vergi Numarası</label>
  <input
    type="text"
    pattern="[0-9]*"
    inputmode="numeric"
    class="form-control @error('tax_number') is-invalid @enderror"
    id="tax_number"
    name="tax_number"
    value="{{ old('tax_number', $company->tax_number) }}"
  >
  @error('tax_number')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

        {{-- Adres --}}
        <div class="form-group mb-3">
          <label for="address">Adres</label>
          <textarea
            class="form-control @error('address') is-invalid @enderror"
            id="address"
            name="address"
            rows="3"
          >{{ old('address', $company->address) }}</textarea>
          @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Telefon --}}
        <div class="form-group mb-3">
  <label for="phone_number">Telefon</label>
  <input
    type="text"
    pattern="[0-9]*"
    inputmode="numeric"
    class="form-control @error('phone_number') is-invalid @enderror"
    id="phone_number"
    name="phone_number"
    value="{{ old('phone_number', $company->phone_number) }}"
  >
  @error('phone_number')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

        {{-- E-Posta --}}
        <div class="form-group mb-3">
          <label for="email">E-Posta</label>
          <input
            type="email"
            class="form-control @error('email') is-invalid @enderror"
            id="email"
            name="email"
            value="{{ old('email', $company->email) }}"
          >
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Kayıt Tarihi --}}
        <div class="form-group mb-3">
          <label for="registration_date">Kayıt Tarihi</label>
          <input
            type="date"
            class="form-control @error('registration_date') is-invalid @enderror"
            id="registration_date"
            name="registration_date"
             value="{{ old('registration_date', \Carbon\Carbon::parse($company->registration_date)->format('Y-m-d')) }}"
          >
          @error('registration_date')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Mevcut Rol --}}
        <div class="form-group mb-3">
          <label for="current_role">Mevcut Rol</label>
          <input
            type="text"
            class="form-control @error('current_role') is-invalid @enderror"
            id="current_role"
            name="current_role"
            value="{{ old('current_role', $company->current_role) }}"
          >
          @error('current_role')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Eğer şirketin bir customer_id’si varsa, gizli input veya select --}}
        <input
          type="hidden"
          name="customer_id"
          value="{{ old('customer_id', $company->customer_id) }}"
        >

        <div class="d-flex justify-content-end">
          <a href="{{ route('companies.index') }}" class="btn btn-secondary me-2">İptal</a>
          <button type="submit" class="btn btn-primary">Güncelle</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
