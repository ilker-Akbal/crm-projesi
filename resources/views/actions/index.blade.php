@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
  
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Actions</h3>
        <div>
          <a href="{{ route('actions.by-customer') }}" class="btn btn-sm btn-info">By Customer</a>
          <a href="{{ route('actions.create') }}"     class="btn btn-sm btn-primary">New Action</a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>User</th>
                <th>Type</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($actions as $a)
                <tr>
                  <td>{{ $a->id }}</td>
                  <td>{{ $a->customer->customer_name }}</td>
                  <td>{{ $a->user->username }}</td>
                  <td>{{ $a->action_type }}</td>
                  <td>{{ $a->action_date }}</td>
                  <td>
                    <a href="{{ route('actions.show', $a) }}"  class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('actions.edit', $a) }}"  class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('actions.destroy', $a) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Del</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="6" class="text-center">No actions found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
