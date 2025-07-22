@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-info">
      <div class="card-header"><h3 class="card-title">Kullanıcı Detayları</h3></div>
      <div class="card-body">
        <p><strong>ID:</strong> {{ $user->id }}</p>
        <p><strong>Kullanıcı Adı:</strong> {{ $user->username }}</p>
        <p><strong>Rol:</strong> {{ ucfirst($user->role) }}</p>
        <p><strong>Aktif:</strong> {{ $user->active ? 'Evet' : 'Hayır' }}</p>
        <p><strong>Parola Hash’i:</strong> {{ $user->password }}</p>
        <small class="text-muted">Bu hash geri döndürülemez; gerçek parola değildir.</small>
      </div>
      <div class="card-footer">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Geri</a>
      </div>
    </div>
  </div>
</section>
@endsection
