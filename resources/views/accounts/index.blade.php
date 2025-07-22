@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">

      <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Accounts</h3>
        <a href="{{ route('accounts.create') }}" class="btn btn-sm btn-primary">Add Account</a>
      </div>

      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer</th>
        
              <th>Opening Date</th>
              <th style="width:140px">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($accounts as $acc)
              <tr>
                <td>{{ $acc->id }}</td>
                <td>{{ $acc->customer->customer_name }}</td>


                <td>{{ \Carbon\Carbon::parse($acc->opening_date)->format('d.m.Y') }}</td>
                <td>
                  <a href="{{ route('accounts.show',  $acc) }}" class="btn btn-xs btn-info">View</a>
                  <a href="{{ route('accounts.edit',  $acc) }}" class="btn btn-xs btn-warning">Edit</a>
                  <form action="{{ route('accounts.destroy', $acc) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Delete?')" class="btn btn-xs btn-danger">Del</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center">No accounts found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>
</section>
@endsection
