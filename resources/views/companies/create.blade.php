@extends('layouts.app')

@section('title', 'Yeni Firma Ekle')

@section('content')
<div class="container">
  <h1>Yeni Firma Ekle</h1>
  <form action="#" method="POST">
    @csrf

    <div class="form-group">
      <label for="company_name">Firma Adı</label>
      <input type="text" class="form-control" id="company_name" name="company_name" required>
    </div>

    <div class="form-group">
      <label for="tax_number">Vergi Numarası</label>
      <input type="text" class="form-control" id="tax_number" name="tax_number">
    </div>

    <div class="form-group">
      <label for="address">Adres</label>
      <textarea class="form-control" id="address" name="address"></textarea>
    </div>

    <div class="form-group">
      <label for="phone_number">Telefon</label>
      <input type="text" class="form-control" id="phone_number" name="phone_number">
    </div>

    <div class="form-group">
      <label for="email">E-posta</label>
      <input type="email" class="form-control" id="email" name="email">
    </div>

    <div class="form-group">
      <label for="registration_date">Kayıt Tarihi</label>
      <input type="date" class="form-control" id="registration_date" name="registration_date">
    </div>

    <div class="form-group">
      <label for="current_role">Cari Rol</label>
      <input type="text" class="form-control" id="current_role" name="current_role">
    </div>

    <button type="submit" class="btn btn-primary">Kaydet</button>
  </form>
</div>
@endsection
