@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-info">
      <div class="card-header"><h3 class="card-title">User Details</h3></div>
      <div class="card-body">
        <p><strong>ID:</strong> {{ $user->id }}</p>
        <p><strong>Username:</strong> {{ $user->username }}</p>
        <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
        <p><strong>Active:</strong> {{ $user->active ? 'Yes' : 'No' }}</p>
      </div>
      <div class="card-footer">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </div>
  </div>
</section>
@endsection
