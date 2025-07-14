@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    @include('partials.alerts')
    <div class="card card-outline card-info">
      <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Actions by Customer</h3>
        <form method="GET" action="{{ route('actions.by-customer') }}" class="form-inline">
          <select name="customer_id" class="form-control mr-2">
            <option value="">-- all customers --</option>
            @foreach($customers as $c)
              <option value="{{ $c->id }}" {{ ($selected==$c->id)?'selected':'' }}>
                {{ $c->customer_name }}
              </option>
            @endforeach
          </select>
          <button class="btn btn-sm btn-primary">Filter</button>
        </form>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>User</th>
                <th>Type</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($actions as $a)
                <tr>
                  <td>{{ $a->id }}</td>
                  <td>{{ $a->customer->customer_name }}</td>
                  <td>{{ $a->user->username }}</td>
                  <td>{{ $a->action_type }}</td>
                  <td>{{ $a->action_date }}</td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center">No actions for this customer.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
