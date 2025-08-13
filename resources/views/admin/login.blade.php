{{-- resources/views/admin/login.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
  :root{
    --bg:#f5f7fb; --grad-a:#7c3aed; --grad-b:#06b6d4; --panel:#ffffff;
    --stroke:rgba(15,23,42,.08); --text:#111827; --muted:#6b7280;
    --shadow:0 18px 40px rgba(2,6,23,.08);
  }
  body{
    min-height:100vh;
    background:
      radial-gradient(1200px 600px at -10% -20%, rgba(124,58,237,.08), transparent 60%),
      radial-gradient(1000px 600px at 110% 10%, rgba(6,182,212,.08), transparent 60%),
      var(--bg);
  }
  .wrap{ min-height:100vh; display:grid; place-items:center; padding:28px; }
  .auth{
    width:100%; max-width:980px; border:1px solid var(--stroke); border-radius:20px; overflow:hidden;
    box-shadow:var(--shadow); display:grid; grid-template-columns:1.05fr 1fr;
    background:linear-gradient(180deg,#fff,#f9fafb);
  }
  @media (max-width:900px){ .auth{ grid-template-columns:1fr; max-width:520px; } }

  .brand-pane{
    background:
      radial-gradient(40% 60% at 20% 20%, rgba(124,58,237,.12), transparent 60%),
      radial-gradient(50% 70% at 90% 10%, rgba(6,182,212,.12), transparent 60%),
      linear-gradient(180deg, #ffffff, #f5f7fb);
    position:relative; padding:42px 36px; color:var(--text); border-right:1px solid var(--stroke);
  }
  .logo{ width:90px; height:90px; object-fit:contain; border-radius:16px; box-shadow:0 10px 30px rgba(124,58,237,.15); display:block; margin-bottom:14px; }
  .title{ font-weight:800; font-size:clamp(1.4rem,2.4vw,2rem) }
  .subtitle{ color:var(--muted); margin-top:6px }

  .form-pane{ padding:36px 32px; background:linear-gradient(180deg,#fff,#f9fafb); }
  .card{ background:var(--panel); border:1px solid var(--stroke); border-radius:16px; padding:24px; box-shadow:var(--shadow); }
  .card.admin-accent{
    box-shadow: 0 18px 40px rgba(2,6,23,.08),
                0 0 0 1px rgba(245,158,11,.35) inset,
                0 0 0 6px rgba(245,158,11,.08);
  }
  .heading{ color:var(--text); font-weight:800; font-size:1.4rem; margin-bottom:6px; display:flex; align-items:center; gap:10px; }
  .muted{ color:var(--muted) }

  .form-group{ margin-top:16px; }
  .label{ display:flex; align-items:center; gap:8px; font-weight:700; color:#111827; }
  .input-wrap{ position:relative; margin-top:8px; }
  .icon{ position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#9ca3af; }
  .form-control{
    width:100%; background:#fff; border:1px solid var(--stroke); border-radius:12px;
    padding:12px 44px 12px 40px; color:#111827; transition:.15s;
  }
  .form-control:focus{ outline:none; border-color:rgba(124,58,237,.45); box-shadow:0 0 0 3px rgba(124,58,237,.18); }
  .password-toggle{
    position:absolute; right:12px; top:50%; transform:translateY(-50%);
    background:none; border:none; color:#6b7280; cursor:pointer;
  }
  .caps-warning{ font-size:.85rem; color:#b45309; display:none; margin-top:6px; }

  .actions{ display:flex; justify-content:space-between; align-items:center; gap:10px; margin-top:14px; }
  .btn-submit{
    width:100%; background:linear-gradient(135deg, var(--grad-b), var(--grad-a)); color:#fff; font-weight:800;
    border:none; border-radius:12px; padding:12px 14px; margin-top:16px;
    box-shadow:0 10px 24px rgba(124,58,237,.25); transition:.12s; position:relative; overflow:hidden;
  }
  .btn-submit:hover{ transform:translateY(-1px); box-shadow:0 14px 32px rgba(124,58,237,.28); }
  .btn-submit[disabled]{ opacity:.75; cursor:not-allowed; }
  .spinner{
    width:18px; height:18px; border-radius:50%;
    border:2px solid rgba(255,255,255,.55); border-top-color:#fff;
    animation:spin .8s linear infinite; display:none; position:absolute; left:14px; top:50%; transform:translateY(-50%);
  }
  .btn-submit.loading .spinner{ display:block; }
  .invalid-feedback{ color:#dc2626; font-size:.85rem; margin-top:6px; }
  @keyframes spin{ to{ transform:translateY(-50%) rotate(360deg); } }
</style>

<div class="wrap">
  <div class="auth">
    {{-- Sol panel --}}
    <div class="brand-pane">
      <img src="{{ asset('images/ika_crm-Photoroom.jpg') }}" alt="İKA Logo" class="logo">
      <div class="title">IKA CRM SİSTEMİ</div>
      <div class="subtitle">Admin girişi ile devam edin.</div>
    </div>

    {{-- Sağ panel (form) --}}
    <div class="form-pane">
      <div class="card admin-accent">
        <div class="heading">
          <i class="fas fa-shield-alt" style="color:#f59e0b"></i>
          Yönetici Girişi
        </div>
        <div class="muted">Lütfen bilgilerinizi girin.</div>

        <form id="adminLoginForm" action="{{ route('admin.login') }}" method="POST" novalidate>
          @csrf

          {{-- Kullanıcı adı --}}
          <div class="form-group">
            <label for="username" class="label">
              <i class="far fa-user-circle"></i> Kullanıcı Adı
            </label>
            <div class="input-wrap">
              <i class="far fa-user icon"></i>
              <input
                type="text"
                id="username"
                name="username"
                class="form-control @error('username') is-invalid @enderror"
                placeholder="admin"
                value="{{ old('username') }}"
                required
                autofocus
                autocomplete="username"
              >
              @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          {{-- Parola --}}
          <div class="form-group">
            <label for="password" class="label">
              <i class="fas fa-lock"></i> Parola
            </label>
            <div class="input-wrap">
              <i class="fas fa-key icon"></i>
              <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="••••••••"
                required
                autocomplete="current-password"
                aria-describedby="capsWarning"
              >
              <button type="button" class="password-toggle" id="togglePassword" aria-label="Parolayı göster/gizle" aria-pressed="false">
                <i class="far fa-eye" aria-hidden="true"></i>
              </button>
              @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div id="capsWarning" class="caps-warning">Caps Lock açık görünüyor.</div>
          </div>

          {{-- Beni hatırla --}}
          <div class="actions">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
              <label class="form-check-label" for="remember">Beni hatırla</label>
            </div>
          </div>

          {{-- Gönder --}}
          <button type="submit" id="submitBtn" class="btn-submit">
            <span class="spinner" aria-hidden="true"></span>
            <span class="btn-text">Giriş Yap</span>
            <span class="btn-loading" style="display:none;">Giriş yapılıyor…</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;800&display=swap" rel="stylesheet">

<script>
  // Şifre göster/gizle
  const toggleBtn = document.getElementById('togglePassword');
  const pwdInput  = document.getElementById('password');
  toggleBtn.addEventListener('click', function () {
    const isHidden = pwdInput.type === 'password';
    pwdInput.type = isHidden ? 'text' : 'password';
    this.setAttribute('aria-pressed', String(isHidden));
    const icon = this.querySelector('i');
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
  });

  // Caps Lock uyarısı
  const capsWarning = document.getElementById('capsWarning');
  function capsCheck(e){
    if(!e.getModifierState) return;
    capsWarning.style.display = e.getModifierState('CapsLock') ? 'block' : 'none';
  }
  pwdInput.addEventListener('keydown', capsCheck);
  pwdInput.addEventListener('keyup', capsCheck);

  // Submit: loading + double-submit engeli ve geri gelince reset
  const form = document.getElementById('adminLoginForm');
  const submitBtn = document.getElementById('submitBtn');
  const btnText = submitBtn.querySelector('.btn-text');
  const btnLoading = submitBtn.querySelector('.btn-loading');
  function setLoading(on){
    submitBtn.disabled = on;
    submitBtn.classList.toggle('loading', on);
    btnText.style.display = on ? 'none' : 'inline';
    btnLoading.style.display = on ? 'inline' : 'none';
  }
  form.addEventListener('submit', () => setLoading(true));
  window.addEventListener('pageshow', () => setLoading(false));
  window.addEventListener('load', () => setLoading(false));
  window.addEventListener('beforeunload', () => setLoading(false));

  @if($errors->has('password')) document.getElementById('password').focus();
  @elseif($errors->has('username')) document.getElementById('username').focus(); @endif
</script>
@endsection
