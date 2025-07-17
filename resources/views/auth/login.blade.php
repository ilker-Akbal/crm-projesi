@extends('layouts.app')

@section('content')

  <div class="container d-flex justify-content-center align-items-center " style="min-height:80vh">
  <div class="card shadow-sm" style="width:380px">
    <div class="card-body">
      <h4 class="mb-4 text-center">CRM Login</h4>

      <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" class="form-control"
                 value="{{ old('username') }}" required autofocus>
          @error('username') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="form-group mb-3">
          <label for="password">Password</label>
          <input type="password" id="password" name="password"
                 class="form-control" required>
        </div>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="remember" name="remember">
          <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
      
    </div>
  </div>
</div>


@endsection
