@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Offers</h3>
        <a href="{{ route('offers.create') }}" class="btn btn-sm btn-primary">Add Offer</a>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Order #</th>
              <th>Offer Date</th>
              <th>Valid Until</th>
              <th>Status</th>
              <th>Total</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($offers as $o)
            <tr>
              <td>{{ $o->id }}</td>
              <td>{{ $o->customer->customer_name }}</td>
              <td>{{ $o->order_id? '#'.$o->order_id : '-' }}</td>
              <td>{{ $o->offer_date }}</td>
              <td>{{ $o->valid_until }}</td>
              <td>{{ ucfirst($o->status) }}</td>
              <td>{{ number_format($o->total_amount,2) }}</td>
              <td>
                <a href="{{ route('offers.show',$o) }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('offers.edit',$o) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('offers.destroy',$o) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button onclick="return confirm('Delete?')" class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center">No offers found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
