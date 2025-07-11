@extends('layouts.app')

@section('content')
<section class="content">
 <div class="container-fluid">
  <div class="card card-primary card-outline">
   <div class="card-header d-flex justify-content-between align-items-center">
     <h3 class="card-title">Companies</h3>
     <a href="{{ route('companies.create') }}" class="btn btn-sm btn-primary">Add Company</a>
   </div>
   <div class="card-body p-0">
    <div class="table-responsive">
     <table class="table table-hover mb-0" id="companies-table">
      <thead>
       <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Tax #</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Customer</th>
        <th>Actions</th>
       </tr>
      </thead>
      <tbody>
       @foreach ($companies as $c)
       <tr>
        <td>{{ $c->id }}</td>
        <td>{{ $c->company_name }}</td>
        <td>{{ $c->tax_number }}</td>
        <td>{{ $c->phone_number }}</td>
        <td>{{ $c->email }}</td>
        <td>{{ $c->customer?->customer_name }}</td>
        <td>
          <a href="{{ route('companies.edit',$c) }}" class="btn btn-sm btn-warning">Edit</a>
          <form action="{{ route('companies.destroy',$c) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
          </form>
        </td>
       </tr>
       @endforeach
      </tbody>
     </table>
    </div>
   </div>
  </div>
 </div>
</section>
@endsection