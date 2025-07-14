@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Companies</h3>
        <a href="{{ route('companies.create') }}" class="btn btn-sm btn-primary">Add Company</a>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Tax #</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Customer</th>
              <th>Role</th>
              <th>Registered</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          @forelse($companies as $c)
            <tr>
              <td>{{ $c->id }}</td>
              <td>{{ $c->company_name }}</td>
              <td>{{ $c->tax_number }}</td>
              <td>{{ $c->phone_number }}</td>
              <td>{{ $c->email }}</td>
              <td>{{ $c->customer?->customer_name }}</td>
              <td>{{ $c->current_role }}</td>
              <td>{{ $c->registration_date }}</td>
              <td>
                <a href="{{ route('companies.show',$c) }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('companies.edit',$c) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('companies.destroy',$c) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button onclick="return confirm('Delete?')" class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="9" class="text-center">No companies found</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
