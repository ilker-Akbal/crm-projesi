@extends('layouts.app')
@section('title','Yeni Firma Ekle')

@section('content')
<div class="container">
  <h1>Yeni Firma Ekle</h1>

  

  <form action="{{ route('companies.store') }}" method="POST">
    @csrf

    <div class="form-group">
      <label for="company_name">Firma Adı *</label>
      <input
        type="text"
        class="form-control @error('company_name') is-invalid @enderror"
        id="company_name"
        name="company_name"
        value="{{ old('company_name') }}"
        required
      >
      @error('company_name')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="tax_number">Vergi Numarası</label>
        <input
  type="text"
  pattern="[0-9]*"
  inputmode="numeric"
  class="form-control @error('tax_number') is-invalid @enderror"
  id="tax_number"
  name="tax_number"
  value="{{ old('tax_number') }}"
>
        @error('tax_number')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group col-md-6">
        <label for="phone_number">Telefon</label>
        <input
  type="text"
  pattern="[0-9]*"
  inputmode="numeric"
  class="form-control @error('phone_number') is-invalid @enderror"
  id="phone_number"
  name="phone_number"
  value="{{ old('phone_number') }}"
>
        @error('phone_number')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="form-group">
      <label for="email">E-posta</label>
      <input
        type="email"
        class="form-control @error('email') is-invalid @enderror"
        id="email"
        name="email"
        value="{{ old('email') }}"
      >
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-group">
      <label for="address">Adres</label>
      <textarea
        class="form-control @error('address') is-invalid @enderror"
        id="address"
        name="address"
        rows="3"
      >{{ old('address') }}</textarea>
      @error('address')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-row">
      <div class="form-group col-md-4">
        <label for="registration_date">Kayıt Tarihi</label>
        <input
          type="date"
          class="form-control @error('registration_date') is-invalid @enderror"
          id="registration_date"
          name="registration_date"
          value="{{ old('registration_date') }}"
        >
        @error('registration_date')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group col-md-4">
        <label for="current_role">Cari Rol *</label>
        <select
          id="current_role"
          name="current_role"
          class="form-control @error('current_role') is-invalid @enderror"
          required
        >
          <option value="" disabled {{ old('current_role') ? '' : 'selected' }}>-- Rol seçiniz --</option>
          <option value="customer"  {{ old('current_role')=='customer'  ? 'selected' : '' }}>Müşteri</option>
          <option value="supplier"  {{ old('current_role')=='supplier'  ? 'selected' : '' }}>Tedarikçi</option>
          <option value="candidate" {{ old('current_role')=='candidate' ? 'selected' : '' }}>Aday</option>
        </select>
        @error('current_role')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <input type="hidden" name="customer_id" 
         value="{{ auth()->user()->customer_id }}">
    </div>

    <button type="submit" class="btn btn-primary">Kaydet</button>
    <a href="{{ route('companies.index') }}" class="btn btn-secondary">İptal</a>
  </form>
</div>
@endsection
