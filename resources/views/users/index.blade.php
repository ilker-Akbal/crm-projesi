@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Users</h3>
        <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">Add User</a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Active</th>
                <th style="width: 120px">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($users as $u)
                <tr>
                  <td>{{ $u->id }}</td>
                  <td>{{ $u->username }}</td>
                  <td>{{ $u->role }}</td>
                  <td>
                    @if($u->active)
                      <span class="badge badge-success">Yes</span>
                    @else
                      <span class="badge badge-danger">No</span>
                    @endif
                  </td>
                  <td>
                    <a href="#" class="btn btn-xs btn-warning">Edit</a>
                    <a href="#" class="btn btn-xs btn-danger">Delete</a>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center">No users found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
