@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-warning card-outline">
      <div class="card-header"><h3 class="card-title">Pending Requests</h3></div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th><th>Customer</th><th>Title</th><th>Registered</th><th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($supports as $s)
                <tr>
                  <td>{{ $s->id }}</td>
                  <td>{{ $s->customer->customer_name }}</td>
                  <td>{{ $s->title }}</td>
                  <td>{{ $s->registration_date }}</td>
                  <td>
                    <a href="{{ route('support.show',$s) }}" class="btn btn-sm btn-info">View</a>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center">No pending requests.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
