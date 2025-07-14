@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Contacts</h3>
        <a href="{{ route('contacts.create') }}" class="btn btn-sm btn-primary">Add Contact</a>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Position</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Company</th>
              <th>Updated At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($contacts as $ct)
            <tr>
              <td>{{ $ct->id }}</td>
              <td>{{ $ct->name }}</td>
              <td>{{ $ct->position }}</td>
              <td>{{ $ct->email }}</td>
              <td>{{ $ct->phone }}</td>
              <td>{{ $ct->company?->company_name }}</td>
              <td>{{ $ct->updated_at }}</td>
              <td>
                <a href="{{ route('contacts.show',$ct) }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('contacts.edit',$ct) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('contacts.destroy',$ct) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button onclick="return confirm('Delete this contact?')" class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center">No contacts found</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
