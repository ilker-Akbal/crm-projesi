{{-- resources/views/admin/customers/create.blade.php --}}
@extends('layouts.app')

@section('title','Yeni Müşteri Ekle')

@push('styles')
<style>
  :root{
    --bg: #f8fafc;
    --panel: rgba(255,255,255,.92);
    --stroke: rgba(15,23,42,.08);
    --text: #1f2937;
    --muted: #4b5563;
    --primary: #7c3aed;
    --primary-2: #06b6d4;
    --danger: #f43f5e;
    --shadow: 0 12px 32px rgba(15, 23, 42, .10);
  }
  body{
    background:
      radial-gradient(1200px 600px at -10% -20%, rgba(124,58,237,.12), transparent 60%),
      radial-gradient(1000px 600px at 110% 10%, rgba(6,182,212,.10), transparent 60%),
      var(--bg);
  }
  .hero{
    position: relative; border-radius: 20px; padding: 20px;
    color: var(--text);
    background:
      linear-gradient(120deg, rgba(124,58,237,.12), rgba(6,182,212,.10)),
      var(--panel);
    border: 1px solid var(--stroke);
    box-shadow: var(--shadow);
    margin-bottom: 18px;
  }
  .hero-title{ font-weight: 800; font-size: 1.5rem }
  .hero-sub{ color: var(--muted) }

  .glass-card{
    background: var(--panel);
    border:1px solid var(--stroke);
    border-radius: 16px;
    padding: 20px;
    box-shadow: var(--shadow);
  }
  label{ font-weight: 600; color: var(--text); }
  .form-control, .form-select, textarea{
    border: 1px solid var(--stroke);
    background: var(--panel);
    color: var(--text);
    border-radius: 10px;
    box-shadow: var(--shadow);
  }
  .form-control:focus, .form-select:focus, textarea:focus{
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(124,58,237,.25);
  }
  .btn-glass{
    border: none;
    border-radius: 10px;
    padding: .55rem 1rem;
    font-weight: 700;
    transition: transform .12s ease, box-shadow .12s ease;
  }
  .btn-primary-grad{
    background: linear-gradient(135deg, var(--primary), var(--primary-2));
    color:#fff;
    box-shadow: 0 6px 18px rgba(124,58,237,.25);
  }
  .btn-secondary-grad{
    background: linear-gradient(135deg, #94a3b8, #64748b);
    color:#fff;
    box-shadow: 0 6px 18px rgba(100,116,139,.25);
  }
  .btn-glass:hover{ transform: translateY(-1px); }
</style>
@endpush

@section('content')
<div class="container py-3">

  {{-- Üst başlık --}}
  <div class="hero">
    <div class="hero-title">Yeni Müşteri Ekle</div>
    <div class="hero-sub">Zorunlu alanlar * ile işaretlenmiştir</div>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger glass-card">
      <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
      </ul>
    </div>
  @endif

  <div class="glass-card">
    <form action="{{ route('admin.customers.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label for="customer_name">Adı *</label>
        <input type="text"
               name="customer_name"
               id="customer_name"
               pattern="[a-zA-ZçÇğĞıİöÖşŞüÜ\s]+"
               title="Müşteri adı sadece harf ve boşluk içerebilir"
               class="form-control @error('customer_name') is-invalid @enderror"
               value="{{ old('customer_name') }}"
               required>
        @error('customer_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="customer_type">Türü *</label>
        <select name="customer_type"
                id="customer_type"
                class="form-select @error('customer_type') is-invalid @enderror"
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

      <div class="mb-3">
        <label for="phone">Telefon</label>
        <input type="text"
               name="phone"
               id="phone"
               pattern="\d{11}"
               maxlength="11"
               title="Telefon numarası 11 haneli olmalıdır"
               class="form-control @error('phone') is-invalid @enderror"
               value="{{ old('phone') }}">
        @error('phone')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
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

      <div class="mb-3">
        <label for="address">Adres</label>
        <textarea name="address"
                  id="address"
                  class="form-control @error('address') is-invalid @enderror"
                  rows="3">{{ old('address') }}</textarea>
        @error('address')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <hr class="my-4">
      <h5 class="mb-3" style="color:var(--primary)">Kullanıcı Girişi</h5>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="username">Kullanıcı Adı *</label>
          <input type="text"
                 name="username"
                 id="username"
                 pattern="[a-zA-Z]+"
                 title="Kullanıcı adı sadece harflerden oluşmalıdır"
                 class="form-control @error('username') is-invalid @enderror"
                 value="{{ old('username') }}"
                 required>
          @error('username')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6 mb-3">
          <label for="password">Şifre *</label>
          <input type="password" name="password" id="password" class="form-control" required>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="password_confirmation">Şifre (Tekrar) *</label>
          <input type="password" name="password_confirmation"
                 id="password_confirmation" class="form-control" required>
        </div>
        <div class="col-md-3 mb-3 d-flex align-items-end">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="active" name="active" checked>
            <label class="form-check-label" for="active">Aktif</label>
          </div>
        </div>

        <div class="col-md-3 mb-3">
          <label for="role">Rol *</label>
          <select name="role" id="role"
                  class="form-select @error('role') is-invalid @enderror"
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

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn-glass btn-primary-grad">
          <i class="fas fa-save me-1"></i> Kaydet
        </button>
        <a href="{{ route('admin.customers.index') }}" class="btn-glass btn-secondary-grad">
          <i class="fas fa-times me-1"></i> İptal
        </a>
      </div>
    </form>
  </div>
</div>
@endsection
