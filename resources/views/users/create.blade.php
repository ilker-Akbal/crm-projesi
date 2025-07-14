{{-- resources/views/users/create.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">

    {{-- Genel flash / validasyon uyarıları --}}
    @include('partials.alerts')

    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Kullanıcı Ekle</h3>
      </div>

      <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="card-body">

          {{-- Kullanıcı adı --------------------------------------------------- --}}
          <div class="form-group">
            <label for="username">Kullanıcı Adı *</label>
            <input  type="text"
                    name="username"
                    id="username"
                    class="form-control @error('username') is-invalid @enderror"
                    value="{{ old('username') }}"
                    required>
            @error('username')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Rol seçimi ------------------------------------------------------ --}}
          <div class="form-group">
            <label for="role">Rol *</label>
            <select name="role"
                    id="role"
                    class="form-control @error('role') is-invalid @enderror"
                    required>
              <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- rol seçiniz --</option>
              @foreach (['admin','manager','user'] as $r)
                <option value="{{ $r }}" {{ old('role') == $r ? 'selected' : '' }}>
                  {{ ucfirst($r) }}
                </option>
              @endforeach
            </select>
            @error('role')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Şifre (isteğe bağlı) ------------------------------------------- --}}
          <div class="form-group">
            <label for="password">Parola <small class="text-muted">(opsiyonel)</small></label>
            <input  type="password"
                    name="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror">
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Aktif mi?  ------------------------------------------------------ --}}
          <div class="form-group mb-0">
            {{-- İşaretlenmediğinde 0 göndermek için gizli input --}}
            <input type="hidden" name="active" value="0">

            <div class="custom-control custom-switch">
              <input  type="checkbox"
                      class="custom-control-input"
                      id="active"
                      name="active"
                      value="1"
                      {{ old('active', true) ? 'checked' : '' }}>
              <label class="custom-control-label" for="active">Aktif mi?</label>
            </div>
            @error('active')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>

        </div> {{-- /.card-body --}}

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('users.index') }}" class="btn btn-secondary mr-2">İptal</a>
          <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
