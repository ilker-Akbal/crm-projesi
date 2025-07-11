@extends('layouts.app')

@section('content')
<section class="content">
 <div class="container-fluid">
  <div class="card card-primary card-outline">
   <div class="card-header d-flex justify-content-between align-items-center">
     <h3 class="card-title">Support Requests</h3>
     <a href="{{ route('support.create') }}" class="btn btn-sm btn-primary">Create Support Request</a>
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
          <th>Actions</th>
        </tr>
       </thead>
       <tbody>
        @forelse($supports as $s)
        <tr>
          <td>{{ $s->id }}</td>
          <td>{{ $s->customer?->customer_name }}</td>
          <td>{{ $s->title }}</td>
          <td>{{ $s->situation }}</td>
          <td>{{ $s->registration_date }}</td>
          <td>
            {{-- show / edit / delete butonları ileride eklenecek --}}
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center">No records yet.</td></tr>
        @endforelse
       </tbody>
     </table>
    </div>
   </div>
  </div>
 </div>
</section>
@endsection
