{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container" style="max-width: 600px;">

    {{-- global flash + hata uyarıları --}}
   

    <div class="card card-outline card-warning shadow-sm rounded">
      <div class="card-header bg-warning">
        <h3 class="card-title text-white">Kullanıcı Düzenle</h3>
      </div>

      <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">

          {{-- Kullanıcı Adı --}}
          <div class="form-group mb-3">
            <label for="username" class="fw-semibold">Kullanıcı Adı <span class="text-danger">*</span></label>
            <input 
  type="text"
  id="username"
  name="username"
  pattern="[a-zA-ZçÇğĞıİöÖşŞüÜ]+"
  title="Kullanıcı adı sadece harflerden oluşmalıdır"
  class="form-control @error('username') is-invalid @enderror"
  value="{{ old('username', $user->username) }}"
  required
>
            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Rol --}}
          <div class="form-group mb-3">
            <label for="role" class="fw-semibold">Rol <span class="text-danger">*</span></label>
            <select 
              id="role" 
              name="role"
              class="form-select @error('role') is-invalid @enderror" 
              required
            >
              <option value="">-- Seçiniz --</option>
              @foreach($roles as $r)
                <option value="{{ $r }}" {{ old('role', $user->role) === $r ? 'selected' : '' }}>
                  {{ ucfirst($r) }}
                </option>
              @endforeach
            </select>
            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Bağlı Müşteri --}}
          <div class="form-group mb-3">
            <label for="customer_id" class="fw-semibold">Bağlı Müşteri <span class="text-danger">*</span></label>
            <select 
              id="customer_id" 
              name="customer_id"
              class="form-select @error('customer_id') is-invalid @enderror" 
              required
            >
              <option value="">-- Seçiniz --</option>
              @foreach($customers as $c)
                <option value="{{ $c->id }}" {{ old('customer_id', $user->customer_id) == $c->id ? 'selected' : '' }}>
                  {{ $c->customer_name }}
                </option>
              @endforeach
            </select>
            @error('customer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Aktif mi? --}}
          <input type="hidden" name="active" value="0">
          <div class="form-check form-switch mb-4">
            <input 
              class="form-check-input" 
              type="checkbox" 
              id="active" 
              name="active"
              value="1" 
              {{ old('active', $user->active) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="active">Aktif Kullanıcı</label>
          </div>

          {{-- (İsteğe bağlı) Şifre Sıfırlama --}}
          <div class="form-group">
            <label for="password">Yeni Parola <small class="text-muted">(boş bırak = değişme)</small></label>
            <input 
              type="password"
              id="password"
              name="password"
              class="form-control @error('password') is-invalid @enderror"
              minlength="8"
            >
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

        </div>

        <div class="card-footer bg-white d-flex justify-content-end">
          <a href="{{ route('admin.users.index') }}" class="btn btn-light me-2">
            <i class="fas fa-arrow-left me-1"></i> Geri
          </a>
          <button type="submit" class="btn btn-warning">
            <i class="fas fa-save me-1"></i> Kaydet
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
