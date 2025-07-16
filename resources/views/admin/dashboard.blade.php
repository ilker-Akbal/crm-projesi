{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Admin Panel</h1>

    {{-- Çıkış Yap Formu --}}
    <form action="{{ route('admin.logout') }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-sm btn-outline-danger">
        Çıkış Yap
      </button>
    </form>
  </div>

  <div class="row gx-4 gy-4">
    {{-- Kullanıcı Yönetimi --}}
    <div class="col-md-6">
      <a href="{{ route('admin.users.index') }}"
         class="card h-100 text-center text-decoration-none text-dark shadow-sm hover:shadow-lg transition">
        <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
          <i class="fas fa-users-cog fa-3x mb-3 text-primary"></i>
          <h4 class="card-title">Kullanıcı Yönetimi</h4>
          <p class="text-muted mb-0">Sistem kullanıcılarını görüntüle ve düzenle</p>
        </div>
      </a>
    </div>

    {{-- Müşteri Yönetimi --}}
    <div class="col-md-6">
      <a href="{{ route('admin.customers.index') }}"
         class="card h-100 text-center text-decoration-none text-dark shadow-sm hover:shadow-lg transition">
        <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
          <i class="fas fa-user-tie fa-3x mb-3 text-secondary"></i>
          <h4 class="card-title">Müşteri Yönetimi</h4>
          <p class="text-muted mb-0">Tüm müşterileri görüntüle ve düzenle</p>
        </div>
      </a>
    </div>
  </div>
</div>
@endsection
