{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title','Yönetim Paneli')  {{-- Başlık Türkçeye çevrildi --}}

@push('styles')
<style>
:root {
  --primary: #4361ee;
  --secondary: #3f37c9;
  --text-dark: #2b2d42;
  --glass-bg: rgba(255,255,255,0.15);
  --danger: #f72585;
}
.glass-card {
  background: var(--glass-bg);
  backdrop-filter: blur(16px) saturate(180%);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.18);
  transition: .3s;
}
.glass-card:hover { transform: translateY(-2px); }
.btn-glass {
  background: linear-gradient(135deg,var(--primary),var(--secondary));
  box-shadow: 0 4px 15px rgba(67,97,238,0.3);
  color: #fff;
  padding: .5rem 1rem;
  border: none;
  border-radius: 8px;
  font-weight:600;
}
.btn-glass-danger {
  background: linear-gradient(135deg,var(--danger),#b5179e);
  box-shadow: 0 4px 15px rgba(247,37,133,0.3);
}
.dashboard-icon {
  font-size:2.5rem;
  background: linear-gradient(135deg,var(--primary),var(--secondary));
  -webkit-background-clip:text;
  color:transparent;
}
.header-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}
</style>
@endpush

@section('content')
<div class="container py-4">
  <div class="header-actions">
    <h2 style="color:var(--text-dark)">Yönetim Paneli</h2>
    {{-- Çıkış butonu admin logout rotasına yönlendirildi --}}
    <form action="{{ route('admin.logout') }}" method="POST">
      @csrf
      <button type="submit" class="btn-glass btn-glass-danger">
        <i class="fas fa-sign-out-alt me-1"></i> Çıkış Yap
      </button>
    </form>
  </div>

  <div class="row g-4">
    <div class="col-md-6">
      <a href="{{ route('admin.users.index') }}" class="glass-card d-block h-100 text-decoration-none">
        <div class="p-4 text-center">
          <div class="dashboard-icon"><i class="fas fa-users-cog"></i></div>
          <h3 class="mt-3" style="color:var(--text-dark)">Kullanıcı Yönetimi</h3>
          <p style="color:var(--text-dark);opacity:.8">Sistem kullanıcılarını yönet</p>
        </div>
      </a>
    </div>
    <div class="col-md-6">
      <a href="{{ route('admin.customers.index') }}" class="glass-card d-block h-100 text-decoration-none">
        <div class="p-4 text-center">
          <div class="dashboard-icon"><i class="fas fa-user-tie"></i></div>
          <h3 class="mt-3" style="color:var(--text-dark)">Müşteri Yönetimi</h3>
          <p style="color:var(--text-dark);opacity:.8">Müşteri kayıtlarını görüntüle & düzenle</p>
        </div>
      </a>
    </div>
  </div>
</div>
@endsection
