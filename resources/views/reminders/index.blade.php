@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
   
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Reminders</h3>
        <a href="{{ route('reminders.create') }}" class="btn btn-sm btn-primary">Add Reminder</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Date</th>
                <th>Customer</th>
                <th>User</th>
                <th>Explanation</th>
                <th style="width:140px">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($reminders as $r)
                <tr>
                  <td>{{ $r->id }}</td>
                  <td>{{ $r->title }}</td>
                  <td>{{ $r->reminder_date }}</td>
                  <td>{{ $r->customer->customer_name }}</td>
                  <td>{{ $r->user->username }}</td>
                  <td>{{ Str::limit($r->explanation, 50) }}</td>
                  <td>
                    <a href="{{ route('reminders.show', $r) }}" class="btn btn-xs btn-info">View</a>
                    <a href="{{ route('reminders.edit', $r) }}" class="btn btn-xs btn-warning">Edit</a>
                    <form action="{{ route('reminders.destroy', $r) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button class="btn btn-xs btn-danger" onclick="return confirm('Delete this reminder?')">Del</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center">No reminders found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
