@extends('layouts.app')
@section('content')
<section class="content">
 <div class="container-fluid">
  <div class="card card-primary card-outline">
   <div class="card-header d-flex justify-content-between align-items-center">
     <h3 class="card-title">Contacts</h3>
     <a href="{{ route('contacts.create') }}" class="btn btn-sm btn-primary">Add Contact</a>
   </div>
   <div class="card-body p-0">
    <div class="table-responsive">
     <table class="table table-hover mb-0" id="contacts-table">
      <thead>
       <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Position</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Company</th>
        <th>Actions</th>
       </tr>
      </thead>
      <tbody>
       @foreach ($contacts as $ct)
       <tr>
        <td>{{ $ct->id }}</td>
        <td>{{ $ct->name }}</td>
        <td>{{ $ct->position }}</td>
        <td>{{ $ct->email }}</td>
        <td>{{ $ct->phone }}</td>
        <td>{{ $ct->company?->company_name }}</td>
        <td>
          <a href="{{ route('contacts.edit',$ct) }}" class="btn btn-sm btn-warning">Edit</a>
          <form action="{{ route('contacts.destroy',$ct) }}" method="POST" class="d-inline">
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
