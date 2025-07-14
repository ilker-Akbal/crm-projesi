@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Support Request Report</h3>
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
                <th>Registered Date</th>
              </tr>
            </thead>
            <tbody>
              @forelse($requests as $r)
                <tr>
                  <td>{{ $r->id }}</td>
                  <td>{{ $r->customer->customer_name }}</td>
                  <td>{{ $r->title }}</td>
                  <td>{{ $r->situation }}</td>
                  <td>{{ $r->registration_date }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center p-4">No data found</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
