@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    @include('partials.alerts')
    <div class="card card-outline card-warning">
      <div class="card-header"><h3 class="card-title">Edit User</h3></div>
      <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
          <div class="form-group">
            <label for="username">Username *</label>
            <input name="username" id="username" class="form-control"
                   value="{{ old('username',$user->username) }}" required>
          </div>
          <div class="form-group">
            <label for="role">Role *</label>
            <select name="role" id="role" class="form-control" required>
              <option value="">-- select role --</option>
              @foreach($roles as $r)
                <option value="{{ $r }}" {{ old('role',$user->role)==$r?'selected':'' }}>
                  {{ ucfirst($r) }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" name="active" class="custom-control-input" id="active"
                     {{ old('active',$user->active)?'checked':'' }}>
              <label class="custom-control-label" for="active">Active?</label>
            </div>
          </div>
        </div>
        <div class="card-footer">
          @include('partials.form-buttons')
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
