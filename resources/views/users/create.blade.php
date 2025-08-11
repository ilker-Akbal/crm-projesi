{{-- resources/views/users/create.blade.php --}}
@extends('layouts.app')

@section('title','Kullanıcı Ekle')

@push('styles')
<style>
  :root{
    --bg:#f8fafc; --panel:rgba(255,255,255,.92); --stroke:rgba(15,23,42,.08);
    --text:#1f2937; --muted:#4b5563; --primary:#7c3aed; --primary-2:#06b6d4;
    --danger:#f43f5e; --shadow:0 12px 32px rgba(15,23,42,.10);
  }
  body{
    background:
      radial-gradient(1200px 600px at -10% -20%, rgba(124,58,237,.12), transparent 60%),
      radial-gradient(1000px 600px at 110% 10%, rgba(6,182,212,.10), transparent 60%),
      var(--bg);
  }
  .hero{
    position:relative; border-radius:20px; padding:20px; color:var(--text);
    background: linear-gradient(120deg,rgba(124,58,237,.12),rgba(6,182,212,.10)), var(--panel);
    border:1px solid var(--stroke); box-shadow:var(--shadow); margin-bottom:18px;
  }
  .hero-title{ font-weight:800; font-size:1.5rem }
  .hero-sub{ color:var(--muted) }
  .glass-card{
    background:var(--panel); border:1px solid var(--stroke); border-radius:16px;
    padding:20px; box-shadow:var(--shadow);
  }
  label{ font-weight:600; color:var(--text) }
  .form-control,.form-select{
    border:1px solid var(--stroke); background:var(--panel); color:var(--text);
    border-radius:10px; box-shadow:var(--shadow);
  }
  .form-control:focus,.form-select:focus{
    border-color:var(--primary); box-shadow:0 0 0 2px rgba(124,58,237,.25);
  }
  .btn-glass{
    border:none; border-radius:10px; padding:.55rem 1rem; font-weight:700;
    transition:transform .12s ease; text-decoration:none; display:inline-flex; align-items:center; gap:.5rem;
  }
  .btn-primary-grad{ background:linear-gradient(135deg,var(--primary),var(--primary-2)); color:#fff; }
  .btn-secondary-grad{ background:linear-gradient(135deg,#94a3b8,#64748b); color:#fff; }
  .btn-danger-grad{ background:linear-gradient(135deg,var(--danger),#b5179e); color:#fff; }
  .btn-glass:hover{ transform:translateY(-1px); }
  .alert-glass{ background:#fff; border:1px solid #fecaca; border-radius:12px; padding:12px; }
</style>
@endpush

@section('content')
<div class="container py-3" style="max-width: 900px;">

  {{-- Üst başlık --}}
  <div class="hero d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
      <div class="hero-title">Kullanıcı Ekle</div>
      <div class="hero-sub">Zorunlu alanlar <strong>*</strong> ile işaretlidir.</div>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn-glass btn-secondary-grad">← Geri</a>
  </div>

  {{-- Hatalar --}}
  @if ($errors->any())
    <div class="alert-glass mb-3">
      <ul class="mb-0">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  {{-- Form --}}
  <div class="glass-card">
    <form action="{{ route('admin.users.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label for="username">Kullanıcı Adı *</label>
        <input  type="text" name="username" id="username"
                pattern="[a-zA-ZçÇğĞıİöÖşŞüÜ]+"
                title="Kullanıcı adı sadece harflerden oluşmalıdır."
                class="form-control @error('username') is-invalid @enderror"
                value="{{ old('username') }}" required>
        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="role">Rol *</label>
          <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- rol seçiniz --</option>
            @foreach (['admin','manager','user'] as $r)
              <option value="{{ $r }}" {{ old('role') == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
            @endforeach
          </select>
          @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
          <label for="customer_id">Bağlı Müşteri *</label>
          <select name="customer_id" id="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
            <option value="" disabled {{ old('customer_id') ? '' : 'selected' }}>-- müşteri seçiniz --</option>
            @foreach($customers as $c)
              <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                {{ $c->customer_name }}
              </option>
            @endforeach
          </select>
          @error('customer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="mb-3">
        <label for="password">Parola <small class="text-muted">(opsiyonel)</small></label>
        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-0">
        <input type="hidden" name="active" value="0">
        <div class="form-check form-switch">
          <input type="checkbox" class="form-check-input" id="active" name="active" value="1"
                 {{ old('active', true) ? 'checked' : '' }}>
          <label class="form-check-label" for="active">Aktif mi?</label>
        </div>
        @error('active') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('admin.users.index') }}" class="btn-glass btn-secondary-grad">İptal</a>
        <button type="submit" class="btn-glass btn-primary-grad">Kaydet</button>
      </div>
    </form>
  </div>
</div>
@endsection
