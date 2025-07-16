@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height:80vh">
  <div class="card" style="width:360px">
    <div class="card-body">
      <h4 class="text-center mb-4">Admin Girişi</h4>
      <form action="{{ route('admin.login') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="username" class="form-label">Kullanıcı Adı</label>
          <input type="text" name="username" id="username"
                 class="form-control @error('username') is-invalid @enderror"
                 value="{{ old('username') }}" required autofocus>
          @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Parola</label>
          <input type="password" name="password" id="password"
                 class="form-control @error('password') is-invalid @enderror"
                 required>
          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
      </form>
    </div>
  </div>
</div>
@endsection
