@extends('layouts.app')
@section('content')
<section class="content">
 <div class="container-fluid">
  <div class="card card-primary card-outline">
   <div class="card-header d-flex justify-content-between align-items-center">
     <h3 class="card-title">Customers</h3>
     <a href="{{ route('customers.create') }}" class="btn btn-sm btn-primary">Add Customer</a>
   </div>
   <div class="card-body p-0">
    <div class="table-responsive">
     <table class="table table-hover mb-0" id="customers-table">
      <thead>
       <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Actions</th>
       </tr>
      </thead>
      <tbody>
       @foreach ($customers as $customer)
       <tr>
        <td>{{ $customer->id }}</td>
        <td>{{ $customer->customer_name }}</td>
        <td>{{ $customer->customer_type }}</td>
        <td>{{ $customer->phone }}</td>
        <td>{{ $customer->email }}</td>
        <td>
            <a href="{{ route('customers.show',$customer) }}" class="btn btn-sm btn-info">View</a>
            <a href="{{ route('customers.edit',$customer) }}" class="btn btn-sm btn-warning">Edit</a>
            <form action="{{ route('customers.destroy',$customer) }}" method="POST" class="d-inline">
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