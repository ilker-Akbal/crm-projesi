@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Yeni Müşteri Ekle</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.customers.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="customer_name">Adı *</label>
            <input type="text"
                   name="customer_name"
                   id="customer_name"
                   class="form-control @error('customer_name') is-invalid @enderror"
                   value="{{ old('customer_name') }}"
                   required>
            @error('customer_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="customer_type">Türü *</label>
            <select name="customer_type"
                    id="customer_type"
                    class="form-control @error('customer_type') is-invalid @enderror"
                    required>
                <option value="" disabled {{ old('customer_type') ? '' : 'selected' }}>-- tür seçiniz --</option>
                <option value="customer" {{ old('customer_type')=='customer' ? 'selected' : '' }}>Müşteri</option>
                <option value="supplier" {{ old('customer_type')=='supplier' ? 'selected' : '' }}>Tedarikçi</option>
                <option value="candidate" {{ old('customer_type')=='candidate' ? 'selected' : '' }}>Aday</option>
            </select>
            @error('customer_type')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">Telefon</label>
            <input type="text"
                   name="phone"
                   id="phone"
                   class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone') }}">
            @error('phone')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">E-posta</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}">
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Adres</label>
            <textarea name="address"
                      id="address"
                      class="form-control @error('address') is-invalid @enderror"
                      rows="3">{{ old('address') }}</textarea>
            @error('address')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <hr>
        <h5>Kullanıcı Girişi</h5>

        <div class="form-row">
          <div class="col-md-6 mb-3">
            <label for="username">Kullanıcı Adı *</label>
            <input type="text" name="username" id="username" class="form-control"
                   value="{{ old('username') }}" required>
          </div>

          <div class="col-md-6 mb-3">
            <label for="password">Şifre *</label>
            <input type="password" name="password" id="password" class="form-control" required>
          </div>
        </div>

        <div class="form-row">
          <div class="col-md-6 mb-4">
            <label for="password_confirmation">Şifre (Tekrar) *</label>
            <input type="password" name="password_confirmation"
                   id="password_confirmation" class="form-control" required>
          </div>
          <div class="col-md-3 mb-4 d-flex align-items-end">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="active" name="active" checked>
              <label class="form-check-label" for="active">Aktif</label>
            </div>
          </div>

          <div class="form-group col-md-3 mb-4">
            <label for="role">Rol *</label>
            <select name="role" id="role"
                    class="form-control @error('role') is-invalid @enderror"
                    required>
              <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- rol seçiniz --</option>
              @foreach($roles as $r)
                <option value="{{ $r }}" {{ old('role') === $r ? 'selected' : '' }}>
                  {{ ucfirst($r) }}
                </option>
              @endforeach
            </select>
            @error('role')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Kaydet</button>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">İptal</a>
    </form>
</div>
@endsection
