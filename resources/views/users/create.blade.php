@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Add User</h3></div>

      <div class="card-body">
        {{-- Demo form – henüz kaydetmez --}}
        <form>
          <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" placeholder="kullanıcı adı">
          </div>

          <div class="form-group">
            <label>Role</label>
            <select class="form-control">
              <option value="admin">Admin</option>
              <option value="manager">Manager</option>
              <option value="user" selected>User</option>
            </select>
          </div>

          <div class="form-group">
            <label>Aktif mi?</label>
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="active">
              <label class="custom-control-label" for="active">Aktif</label>
            </div>
          </div>

          <button type="submit" class="btn btn-primary">Kaydet (demo)</button>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
