@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
 
    <div class="card card-primary card-outline">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Support Requests</h3>
        <a href="{{ route('support.create') }}" class="btn btn-sm btn-primary">New Request</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Title</th>
                <th>Status</th>
                <th>Registered</th>
                <th>Last Updated</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($supports as $s)
                <tr>
                  <td>{{ $s->id }}</td>
                  <td>{{ $s->customer->customer_name }}</td>
                  <td>{{ $s->Title }}</td>
                  <td class="text-capitalize">{{ $s->situation }}</td>
                  <td>{{ $s->registration_date }}</td>
                  <td>{{ $s->updated_at->format('Y-m-d') }}</td>
                  <td>
                    <a href="{{ route('support.show', $s) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('support.edit', $s) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('support.destroy', $s) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center">No requests found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
