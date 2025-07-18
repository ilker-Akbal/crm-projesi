@extends('layouts.app')

@section('content')
<style>
  :root {
    --primary-color: #4361ee;
    --secondary-color: #3f37c9;
    --text-dark: #2b2d42;
    --text-light: #f8f9fa;
    --glass-opacity: 0.15;
    --glass-border: 1px solid rgba(255, 255, 255, 0.25);
  }

  .glass-card {
    background: rgba(255, 255, 255, var(--glass-opacity));
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    border: var(--glass-border);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
    transition: all 0.3s ease;
  }

  .glass-card .form-control {
    background: rgba(255, 255, 255, 0.3);
    border: none;
    border-radius: 8px;
    color: var(--text-dark);
    padding: 12px 16px;
    height: auto;
    transition: all 0.3s ease;
    margin-top: 8px;
  }

  .glass-card .form-control:focus {
    background: rgba(255, 255, 255, 0.4);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
  }

  .input-label {
    display: block;
    color: var(--text-dark);
    font-weight: 500;
    margin-bottom: 4px;
    font-size: 0.9rem;
  }

  .btn-glass {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border: none;
    border-radius: 8px;
    padding: 12px;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    width: 100%;
    margin-top: 16px;
  }

  .btn-glass:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
    color: white;
  }

  .invalid-feedback {
    font-size: 0.8rem;
    margin-top: 0.25rem;
    color: #ff4d4f;
    font-weight: 500;
  }

  .brand-logo {
    width: 80px;
    height: 80px;
    object-fit: contain;
    margin: 0 auto 1rem;
    display: block;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
  }

  .brand-title {
    font-weight: 700;
    color: var(--text-dark);
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    text-align: center;
  }

  .brand-subtitle {
    font-size: 1rem;
    font-weight: 500;
    color: var(--text-dark);
    opacity: 0.8;
    margin-bottom: 1rem;
    text-align: center;
    display: block;
  }

  .form-group {
    margin-bottom: 1.5rem;
  }

  @media (max-width: 576px) {
    .glass-card {
      padding: 2rem 1.5rem !important;
    }
  }
</style>

<div class="d-flex vh-100 justify-content-center align-items-center px-3">
  <div class="glass-card p-4 p-md-5 mx-auto" style="max-width: 400px; width:100%;">
    <!-- Logo ve Başlık -->
    <div class="mb-4">
      <img src="{{ asset('images/ika_crm-Photoroom.jpg') }}" alt="IKA Logo" class="brand-logo" style="width: 120px; height: 120px;">
      <h3 class="brand-title">IKA CRM SYSTEM</h3>
      <p class="brand-subtitle">Hoşgeldiniz, giriş yaparak devam edin</p>
    </div>

    <!-- Form -->
    <form action="{{ route('login') }}" method="POST">
      @csrf

      <div class="form-group">
        <label for="username" class="input-label">Kullanıcı Adı</label>
        <input 
          type="text" 
          id="username" 
          name="username" 
          class="form-control @error('username') is-invalid @enderror"
          placeholder="kullanıcı adınız"
          value="{{ old('username') }}" 
          required 
          autofocus
        >
        @error('username')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="password" class="input-label">Parola</label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          class="form-control @error('password') is-invalid @enderror"
          placeholder="••••••••"
          required
        >
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn btn-glass">
        Giriş Yap
      </button>
    </form>
  </div>
</div>

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection
